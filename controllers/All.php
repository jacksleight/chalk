<?php
namespace Ayre\Controller;

use Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class All extends Action
{
	public function preDispatch(Request $req, Response $res)
	{
		$req->view = (object) [];
	}

	public function postDispatch(Request $req, Response $res)
	{
		$path = isset($req->view->path)
			? $req->view->path
			: "/{$req->controller}/{$req->action}";
		return $res->html($this->view->render($path, [
			'req' => $req,
			'res' => $res,
		] + (array) $req->view));
	}
}