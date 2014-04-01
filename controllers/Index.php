<?php
namespace Ayre\Controller;

use Coast\App\Controller\Action,
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
}