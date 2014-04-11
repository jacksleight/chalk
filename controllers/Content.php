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

class Content extends Action
{
	public function preDispatch(Request $req, Response $res)
	{
		$req->view->entityType
			= $req->entityType
			= Ayre::type(isset($req->entityType) ? $req->entityType : 'core-content');
	}

	public function postDispatch(Request $req, Response $res)
	{
		$path = "{$req->entityType->local->path}/{$req->action}";
		if ($this->view->has($path)) {
			$req->view->path = $path;
		}
	}

	public function index(Request $req, Response $res)
	{}

	public function edit(Request $req, Response $res)
	{
		$req->view->entity = $wrap = $this->entity->wrap(
			$entity = $this->entity($req->entityType->class)->findOrCreate($req->id)
		);

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
				])->toString();
			}
		}
		return $res
			->headers($headers)
			->json(['files' => $uploads]);
	}

	public function archive(Request $req, Response $res)
	{
		$entity = $this->entity($req->entityType->class)->find($req->id);

		$entity->status = \Ayre::STATUS_ARCHIVED;
		$this->entity->flush();

		return $res->redirect($this->url(array(
			'action'	=> null,
			'id'		=> null,
		)));
	}
}