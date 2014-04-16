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
		$entity = $this->entity($req->entityType->class)->findOrCreate($req->id);
		if ($entity->status == \Ayre::STATUS_PUBLISHED) {
			$entity = $entity->duplicate();
		}
		$req->view->entity = $wrap = $this->entity->wrap($entity);

		if (!$req->isPost()) {
			return;
		}

		$wrap->graphFromArray($req->bodyParams());
		if (!$wrap->graphIsValid()) {
			return;
		}

		if (!$this->entity->isPersisted($entity)) {
			$this->entity->persist($entity);
		}
		$this->entity->flush();

		return $res->redirect($this->url(array(
			'action'	=> null,
			'id'		=> null,
		)));
	}

	public function upload(Request $req, Response $res)
	{
		if (!$req->isPost()) {
			throw new \Ayre\Exception("Upload only accepts post requests");
		}

		$dir      = new \Coast\Dir('data/temp/upload', true);
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
				$file = new \Ayre\Entity\File();
				$file->file($temp);
				$this->entity->persist($file);
				$this->entity->flush();
				$temp->remove();
				$upload->jack = $file->file->name();
				$upload->html = $this->view->render('/content/thumb', [
					'content'	=> $file,
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
		$entity = $this->entity($req->entityType->class)->find($req->id);

		$entity->status = $req->status;
		if ($entity->status == \Ayre::STATUS_ARCHIVED && isset($entity->previous)) {
			$entity->previous->status = $entity->status;
		}
		$this->entity->flush();

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
		$entity = $this->entity($req->entityType->class)->find($req->id);

		$entity = $entity->restore();
		$this->entity->persist($entity);
		$this->entity->flush();

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