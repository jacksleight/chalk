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
		$req->type = Ayre::type($req->type);
	}

	public function postDispatch(Request $req, Response $res)
	{
		$path = $this->view->has("{$req->type->local->path}/{$req->action}")
			? "{$req->type->local->path}/{$req->action}"
			: "{$req->controller}/{$req->action}";
		return $res->html($this->view->render($path, array(
			'req' => $req,
			'res' => $res,
		)));
	}

	public function index(Request $req, Response $res)
	{}

	public function edit(Request $req, Response $res)
	{
		$wrap = $this->entity->wrap(
			$content = $this->entity($req->type->class)->findOrCreate($req->id)
		);
		$this->req->wrap = $wrap;

		if (!$req->isPost()) {
			return;
		}

		$wrap->graphFromArray($req->bodyParams());
		if (!$wrap->graphIsValid()) {
			return;
		}

		if (!$this->entity->isPersisted($content)) {
			$this->entity->persist($content);
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
}