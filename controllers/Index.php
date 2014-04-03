<?php
namespace Ayre\Controller;

use FileUpload,
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
			return true;
		}

		$pathResolver = new FileUpload\PathResolver\Simple((new \Coast\Dir('data/temp/upload', true))->name());
		$fileSystem   = new FileUpload\FileSystem\Simple();
		$fileUpload   = new FileUpload\FileUpload($_FILES['files'], $_SERVER);
		$fileUpload->setPathResolver($pathResolver);
		$fileUpload->setFileSystem($fileSystem);

		list($uploads, $headers) = $fileUpload->processAll();
		foreach ($uploads as $upload) {
			if (isset($upload->path)) {
				$file	= new \Coast\File($upload->path);
				$entity	= new \Ayre\Entity\File();
				$entity->file = $file;
				$this->entity->persist($entity);
				$this->entity->flush();
				$file->remove();
			}
		}
		
		foreach ($headers as $name => $value) {
			$res->header($name, $value);
		}
		return $res->json(['files' => $uploads]);
	}
}