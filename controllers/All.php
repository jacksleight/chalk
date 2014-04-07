<?php
namespace Ayre\Controller;

use Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class All extends Action
{
	public function postDispatch(Request $req, Response $res)
	{
		$path = "/{$req->controller}/{$req->action}";
		return $res->html($this->view->render($path, array(
			'req' => $req,
			'res' => $res,
		)));
	}
}