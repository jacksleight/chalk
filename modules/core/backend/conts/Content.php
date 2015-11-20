<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\App as Chalk;
use Chalk\Controller\Basic;
use Chalk\Core;
use Coast\Request;
use Coast\Response;
use Coast\Url;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use FileUpload\FileSystem;
use FileUpload\FileUpload;
use FileUpload\PathResolver;

class Content extends Basic
{
	public function preDispatch(Request $req, Response $res)
	{
		$req->view->info
			= $req->info
			= Chalk::info($req->entity ? $req->entity : 'Chalk\Core\Content');
	}

	public function index(Request $req, Response $res)
	{
		if (!$req->entity) {
			if (count($this->contentList)) {
				$class = $this->contentList->first();
				return $res->redirect($this->url(['entity' => $class->name]));
			}
		}

		$class = "\\{$req->info->module->class}\\Model\\{$req->info->local->class}\\Index";
		if (!class_exists($class)) {
			$class = "\Chalk\Core\Model\Content\Index";
		}
		$index = new $class();
		$req->view->index = $wrap = $this->em->wrap($index);
		$wrap->graphFromArray($req->queryParams());

		if (!isset($index->batch)) {
			return;
		}

		try {
			$notice = null;
			foreach ($index->contents as $content) {
				if ($index->batch == 'publish') {
					$notice = 'published';
					$content->status = Chalk::STATUS_PUBLISHED;
				} else if ($index->batch == 'archive') {
					$notice = 'archived';
					$content->status = Chalk::STATUS_ARCHIVED;
				} else if ($index->batch == 'restore') {
					$notice = 'restored';
					$content->restore();
				} else if ($index->batch == 'delete') {
					$notice = 'deleted';
					$this->em->remove($content);
				}
			}
			$this->em->flush();
		} catch (ForeignKeyConstraintViolationException $e) {
			if (isset($notice)) {
				$this->notify("{$req->info->singular} <strong>{$content->name}</strong> cannot be deleted because it is in use", 'negative');
			}
			return;
		}

		if (isset($notice)) {
			$this->notify("{$req->info->plural} were {$notice} successfully", 'positive');
		}
		return $res->redirect($this->url->query(array(
			'batch' => null,
		)));
	}

	public function edit(Request $req, Response $res)
	{
		$content = isset($req->route['params']['content'])
			? $this->em($req->info)->id($req->route['params']['content'])
			: $this->em($req->info)->create();
		$req->view->content = $wrap = $this->em->wrap($content);
		if ($content->isNew()) {
			$wrap->graphFromArray($req->queryParams());
		}

		if (!$req->isPost()) {
			return;
		}

		$wrap->graphFromArray($req->bodyParams());
		if (!$wrap->graphIsValid()) {
			$this->notify("{$req->info->singular} could not be saved, please check the messages below", 'negative');
			return;
		}

		if (!$this->em->isPersisted($content)) {
			$this->em->persist($content);
		}
		$this->em->flush();

		$this->notify("{$req->info->singular} <strong>{$content->name}</strong> was saved successfully", 'positive');
		return $res->redirect($this->url(array(
			'action'	=> 'edit',
			'content'	=> $content->id,
		)));
	}

	public function quick(Request $req, Response $res)
	{
		if (!$req->isPost()) {
			throw new \Chalk\Exception("Upload action only accepts POST requests");
		}
		
		$quick = new \Chalk\Core\Model\Url\Quick();
		$wrap  = $this->em->wrap($quick);

		$wrap->graphFromArray($req->bodyParams());
		if (!$wrap->graphIsValid()) {
			$this->notify("{$req->info->singular} could not be added, please try again", 'negative');
			return $res->redirect($this->url(array(
				'action' => 'index',
			)));
			return;
		}

		$redirect = new Url($req->redirect);

		$content = $this->em($req->info)->one([
			'url' => $quick->url,
		]);
		if ($content) {
			$redirect->queryParam('contentNew', $content->id);
			return $res->redirect($redirect);
		}

		$content = $this->em($req->info)->create();
		$content->status = \Chalk\App::STATUS_PUBLISHED;
		$content->fromArray($quick->toArray());

		$this->em->persist($content);
		$this->em->flush();

		$redirect->queryParam('contentNew', $content->id);
		return $res->redirect($redirect);
	}

	public function upload(Request $req, Response $res)
	{
		if (!$req->isPost()) {
			throw new \Chalk\Exception("Upload action only accepts POST requests");
		}

		$dir      = $this->chalk->config->dataDir->dir('upload', true);
		$uploader = new FileUpload($_FILES['files'], $req->servers());
		$uploader->setPathResolver(new PathResolver\Simple($dir->name()));
		$uploader->setFileSystem(new FileSystem\Simple());

		list($uploads, $headers) = $uploader->processAll();
		foreach ($uploads as $upload) {
			if (isset($upload->path)) {
				$content = isset($req->route['params']['content'])
					? $this->em($req->info)->id($req->route['params']['content'])
					: $this->em($req->info)->create();		
				$view = $content->isNew() ? 'content/thumb' : 'content/card-upload';	
				if (!$this->em->isPersisted($content)) {
					$content->newFile = new \Coast\File($upload->path);
					$this->em->persist($content);
				} else {
					$content->move(new \Coast\File($upload->path));
				}
				$this->em->flush();
				unset($upload->path);
				$upload->html = $this->view->render($view, [
					'content'		=> $content,
					'covered'		=> true,
					'isEditAllowed'	=> (bool) $req->isEditAllowed,
				] + (array) $req->view, 'core')->toString();
			}
		}

		return $res
			->headers($headers)
			->json(['files' => $uploads]);
	}

	public function archive(Request $req, Response $res)
	{
		$content = $this->em($req->info)->find($req->content);

		$content->status = Chalk::STATUS_ARCHIVED;
		$this->em->flush();

		$this->notify("{$req->info->singular} <strong>{$content->name}</strong> was archived successfully", 'positive');
		if (isset($req->redirect)) {
			return $res->redirect($req->redirect);
		} else {
			return $res->redirect($this->url(array(
				'action'	=> 'edit',
				'content'	=> $content->id,
			)));
		}
	}

	public function restore(Request $req, Response $res)
	{
		$content = $this->em($req->info)->find($req->content);

		$content->restore();
		$this->em->flush();

		$this->notify("{$req->info->singular} <strong>{$content->name}</strong> was restored successfully", 'positive');
		if (isset($req->redirect)) {
			return $res->redirect($req->redirect);
		} else {
			return $res->redirect($this->url(array(
				'action'	=> 'edit',
				'content'	=> $content->id,
			)));
		}
	}

	public function delete(Request $req, Response $res)
	{
		$content = $this->em($req->info)->find($req->content);

		try {
			$this->em->remove($content);
			$this->em->flush();
		} catch (ForeignKeyConstraintViolationException $e) {
			$this->notify("{$req->info->singular} <strong>{$content->name}</strong> cannot be deleted because it is in use", 'negative');
			if (isset($req->redirect)) {
				return $res->redirect($req->redirect);
			} else {
				return $res->redirect($this->url(array(
					'action'	=> 'edit',
					'content'	=> $content->id,
				)));
			}
		}

		$this->notify("{$req->info->singular} <strong>{$content->name}</strong> was deleted successfully", 'positive');
		return $res->redirect($this->url(array(
			'action'	=> 'index',
			'content'	=> null,
		)));
	}
}