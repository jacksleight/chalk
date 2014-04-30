<?php
namespace Ayre\Controller;

use Ayre,
	Ayre\Entity,
	FileUpload\FileUpload,
	FileUpload\PathResolver,
	FileUpload\FileSystem,
	Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class Content extends Ayre\Controller\Entity
{
	public function preDispatch(Request $req, Response $res)
	{
		$req->view->entityType
			= $req->entityType
			= Ayre::type(isset($req->entityType) ? $req->entityType : 'Ayre\Entity\Content');
	}

	public function postDispatch(Request $req, Response $res)
	{
		$req->view->path = "{$req->entityType->local->path}/{$req->action}";
	}

	public function index(Request $req, Response $res)
	{}

	public function edit(Request $req, Response $res)
	{
		$entity = $this->em($req->entityType->class)->fetchOrCreate($req->id);
		if ($entity->status == \Ayre::STATUS_PUBLISHED) {
			$entity = $entity->duplicate();
		}
		$req->view->entity = $wrap = $this->em->wrap($entity);

		if (!$req->isPost()) {
			return;
		}

		$wrap->graphFromArray($req->bodyParams());
		if (!$wrap->isValid()) {
			return;
		}

		if (!$this->em->isPersisted($entity)) {
			$this->em->persist($entity);
		}
		$this->em->flush();

		return $res->redirect($this->url(array(
			'action'	=> null,
			'id'		=> null,
		)));
	}

	public function upload(Request $req, Response $res)
	{
		if (!$req->isPost()) {
			throw new \Ayre\Exception("Upload action only accepts POST requests");
		}

		$dir      = $this->root->dir('data/upload', true);
		$uploader = new FileUpload($req->bodyParam('files'), $req->servers());
		$uploader->setPathResolver(new PathResolver\Simple($dir->name()));
		$uploader->setFileSystem(new FileSystem\Simple());

		list($uploads, $headers) = $uploader->processAll();
		foreach ($uploads as $upload) {
			if (isset($upload->path)) {
				$temp = new \Coast\File($upload->path);
				// Gedmo\Uploadable Fails on duplicate file names with no extenstion
				if (!$temp->extName()) {
					$temp->rename(['extName' => 'bin']);
				}
				$entity = new \Ayre\Entity\File();
				$entity->file($temp);
				$this->em->persist($entity);
				$this->em->flush();
				$entity->makePathRelative();
				$this->em->flush();
				$temp->remove();
				unset($upload->path);
				$upload->html = $this->view->render('file/thumb', [
					'entity'	=> $entity,
					'covered'	=> true,
				] + (array) $req->view)->toString();
			}
		}

		return $res
			->headers($headers)
			->json(['files' => $uploads]);
	}

	public function status(Request $req, Response $res)
	{
		$entity = $this->em($req->entityType->class)->find($req->id);

		$entity->status = $req->status;
		if ($entity->status == \Ayre::STATUS_ARCHIVED && isset($entity->previous)) {
			$entity->previous->status = $entity->status;
		}
		$this->em->flush();

		if ($entity->status == \Ayre::STATUS_ARCHIVED) {
			return $res->redirect($this->url(array(
				'action' => null,
				'id'	 => null,
			)));
		} else {
			return $res->redirect($this->url(array(
				'action' => 'edit',
			)));
		}
	}

	public function restore(Request $req, Response $res)
	{
		$entity = $this->em($req->entityType->class)->find($req->id);

		$entity = $entity->restore();
		$this->em->persist($entity);
		$this->em->flush();

		return $res->redirect($this->url(array(
			'action' => 'edit',
			'id'	 => $entity->id,
		)));
	}

	public function delete(Request $req, Response $res)
	{
		throw new \Exception();
	}
}