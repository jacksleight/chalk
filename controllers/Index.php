<?php
namespace Ayre\Controller;

use FileUpload\FileUpload,
	FileUpload\PathResolver,
	FileUpload\FileSystem,
	Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class Index extends Action
{
	public function index(Request $req, Response $res)
	{
		$path = $req->path();
		$path = '/' . (strlen($path) ? $path : 'index');
		return $res->html($this->view->render($path, array(
			'req' => $req,
			'res' => $res,
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

		list($files, $headers) = $uploader->processAll();
		foreach ($files as $file) {
			if (isset($file->path)) {
				$temp = new \Coast\File($file->path);
				// Gedmo\Uploadable Fails on duplicate file names with no extenstion
				if (!$temp->extName()) {
					$temp->rename(['extName' => 'bin']);
				}
				$entity	= new \Ayre\Entity\File();
				$entity->file = $temp;
				$this->entity->persist($entity);
				$this->entity->flush();
				$temp->remove();
				$file->url = $this->url($entity->file, true, true, false)->toString();
			}
		}
		return $res
			->headers($headers)
			->json(['files' => $files]);
	}
}