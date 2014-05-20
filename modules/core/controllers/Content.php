<?php
namespace Ayre\Controller;

use Ayre,
	Ayre\Core,
	FileUpload\FileUpload,
	FileUpload\PathResolver,
	FileUpload\FileSystem,
	Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class Content extends Ayre\Controller\Basic
{
	public function preDispatch(Request $req, Response $res)
	{
		$req->view->entityType
			= $req->entityType
			= Ayre::type(isset($req->entityType) ? $req->entityType : 'Ayre\Core\Content');
	}

	public function postDispatch(Request $req, Response $res)
	{}

	public function index(Request $req, Response $res)
	{
		$wrap = $this->em->wrap($index = new \Ayre\Core\Model\Index());
		$wrap->graphFromArray($req->queryParams());
		$req->view->index = $wrap;
	}

	public function edit(Request $req, Response $res)
	{
		$content = $this->em($req->entityType->class)->fetchOrCreate($req->id);
		$req->view->content = $wrap = $this->em->wrap($content);

		if (!$req->isPost()) {
			return;
		}

		$wrap->graphFromArray($req->bodyParams());
		if (!$wrap->isValid()) {
			return;
		}

		if (!$this->em->isPersisted($content)) {
			$this->em->persist($content);
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
				$content = new \Ayre\Core\File();
				$content->newFile = new \Coast\File($upload->path);
				$this->em->persist($content);
				$this->em->flush();
				unset($upload->path);
				$upload->html = $this->view->render('file/thumb', [
					'content'	=> $content,
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
		$content = $this->em($req->entityType->class)->find($req->id);

		$content->status = $req->status;
		$this->em->flush();

		if ($content->status == \Ayre::STATUS_ARCHIVED) {
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
		$content = $this->em($req->entityType->class)->find($req->id);

		$content->status = \Ayre::STATUS_PUBLISHED;
		$this->em->flush();

		return $res->redirect($this->url(array(
			'action' => 'edit',
			'id'	 => $content->id,
		)));
	}

	public function delete(Request $req, Response $res)
	{
		throw new \Exception();
	}
}