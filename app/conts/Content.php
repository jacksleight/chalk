<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Controller;

use Chalk\Chalk,
	Chalk\Core,
	FileUpload\FileUpload,
	FileUpload\PathResolver,
	FileUpload\FileSystem,
	Chalk\Controller\Basic,
	Coast\Request,
	Coast\Response,
	Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

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
			$items = $req->view->navigation->items('Chalk\Core\Content');
			foreach ($items as $item) {
				if (!isset($item)) {
					continue;
				}
				return $res->redirect($this->url($item['url'][0], $item['url'][1], true));
			}
		}

		$index = new \Chalk\Core\Model\Index();
		$req->view->index = $wrap = $this->em->wrap($index);
		$wrap->graphFromArray($req->queryParams());

		if (!isset($index->action)) {
			return;
		}

		foreach ($index->contents as $content) {
			if ($index->action == 'archive') {
				$content->status = \Chalk\Chalk::STATUS_ARCHIVED;
			} else if ($index->action == 'restore') {
				$content->restore();
			} else if ($index->action == 'delete') {
				$this->em->remove($content);
			}
		}
		$this->em->flush();

		$this->notify("{$req->info->plural} were {$index->action}d successfully", 'positive');
		return $res->redirect($this->url->query(array(
			'action' => null,
		)));
	}

	public function edit(Request $req, Response $res)
	{
		$content = isset($req->route['params']['content'])
			? $this->em($req->info)->id($req->route['params']['content'])
			: $this->em($req->info)->create();
		$req->view->content = $wrap = $this->em->wrap($content);

		if (!$req->isPost()) {
			return;
		}

		$params = $req->bodyParams();
		$status = isset($params['status']) ? $params['status'] : $wrap->status;
		unset($params['status']);
		$wrap->graphFromArray($params);
		$wrap->graphFromArray(['status' => $status]); // @hack setting dates should be a listener
		if (!$wrap->isValid()) {
			return;
		}


		if (!$this->em->isPersisted($content)) {
			$this->em->persist($content);
		}
		$this->em->flush();

		die;

		$this->notify("{$req->info->singular} <strong>{$content->name}</strong> was saved successfully", 'positive');
		return $res->redirect($this->url(array(
			'action'	=> 'edit',
			'content'	=> $content->id,
		)));
	}

	public function upload(Request $req, Response $res)
	{
		if (!$req->isPost()) {
			throw new \Chalk\Exception("Upload action only accepts POST requests");
		}

		$dir      = $this->frontend->dir('data/upload', true);
		$uploader = new FileUpload($req->bodyParam('files'), $req->servers());
		$uploader->setPathResolver(new PathResolver\Simple($dir->name()));
		$uploader->setFileSystem(new FileSystem\Simple());

		list($uploads, $headers) = $uploader->processAll();
		foreach ($uploads as $upload) {
			if (isset($upload->path)) {
				$content = $this->em($req->info)->create();
				$content->newFile = new \Coast\File($upload->path);
				$this->em->persist($content);
				$this->em->flush();
				unset($upload->path);
				$upload->html = $this->view->render('content/thumb', [
					'content'	=> $content,
					'covered'	=> true,
				] + (array) $req->view)->toString();
			}
		}

		return $res
			->headers($headers)
			->json(['files' => $uploads]);
	}

	public function archive(Request $req, Response $res)
	{
		$content = $this->em($req->info)->find($req->content);

		$content->status = \Chalk\Chalk::STATUS_ARCHIVED;
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
			$this->notify("{$req->info->singular} <strong>{$content->name}</strong> cannot be deleted as it is in use", 'negative');
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