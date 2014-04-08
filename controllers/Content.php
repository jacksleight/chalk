<?php
namespace Ayre\Controller;

use Ayre,
	FileUpload\FileUpload,
	FileUpload\PathResolver,
	FileUpload\FileSystem,
	Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class Content extends Action
{
	public function index(Request $req, Response $res)
	{}

	public function edit(Request $req, Response $res)
	{
		$content = $this->entity($req->type)->findOrCreate($req->id);

		if (!$req->isPost()) {
			return true;
		}

		$wrap->graphFromArray($this->request->getPostParams());
		if (!$wrap->graphIsValid()) {
			return;
		}

		if (!$content->isPersisted()) {
			$this->em->persist($content);
		}
		$this->em->flush();

		return $this->redirect($this->url(array(
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
					'file'		=> $file,
					'covered'	=> true,
				])->toString();
			}
		}
		return $res
			->headers($headers)
			->json(['files' => $uploads]);
	}
}