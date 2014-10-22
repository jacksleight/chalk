<?php
namespace Chalk\Core\Controller;

use Chalk\Chalk,
	Chalk\Core,
	FileUpload\FileUpload,
	FileUpload\PathResolver,
	FileUpload\FileSystem,
	Chalk\Controller\Basic,
	Coast\Request,
	Coast\Response;

class Content extends Basic
{
	public function preDispatch(Request $req, Response $res)
	{
		$req->view->entity
			= $req->entity
			= Chalk::entity($req->entity ? $req->entity : 'Chalk\Core\Content');
	}

	public function index(Request $req, Response $res)
	{
		$wrap = $this->em->wrap($index = new \Chalk\Core\Model\Index());
		$wrap->graphFromArray($req->queryParams());
		$req->view->index = $wrap;
	}

	public function select(Request $req, Response $res)
	{
		$wrap = $this->em->wrap($index = new \Chalk\Core\Model\Index());
		$wrap->graphFromArray($req->bodyParams());
		$req->view->index = $wrap;

		if (count($index->contents)) {
			$contents = [];
			foreach ($index->contents as $content) {
				$contents[] = [
					'id'	=> $content->master->id,
					'name'	=> $content->master->name,
					'card'	=> $this->view->render('content/card', ['content' => $content])->toString(),
				];
			}
			return $res->json(['contents' => $contents]);
		}
	}

	public function edit(Request $req, Response $res)
	{
		$content = isset($req->route['params']['content'])
			? $this->em($req->entity)->id($req->route['params']['content'])
			: $this->em($req->entity)->create();
		$req->view->content = $wrap = $this->em->wrap($content);

		if (!$req->isPost()) {
			return;
		}

		$params = $req->bodyParams();
		$status = $params['status'];
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
				$content = new \Chalk\Core\File();
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
		$content = $this->em($req->entity)->find($req->content);

		$content->status = \Chalk\Chalk::STATUS_ARCHIVED;
		$this->em->flush();

		return $res->redirect($this->url(array(
			'action'	=> 'edit',
			'content'	=> $content->id,
		)));
	}

	public function restore(Request $req, Response $res)
	{
		$content = $this->em($req->entity)->find($req->content);

		$content->restore();
		$this->em->flush();

		return $res->redirect($this->url(array(
			'action'	=> 'edit',
			'content'	=> $content->id,
		)));
	}

	public function delete(Request $req, Response $res)
	{
		$entity = $this->em($req->entity)->find($req->content);

		$this->em->remove($entity);
		$this->em->flush();

		return $res->redirect($this->url(array(
			'action'	=> 'index',
			'id'		=> null,
		)));
	}
}