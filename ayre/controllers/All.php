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

		$name	= "criteria_" . md5(serialize($req->route['params']));
		$value	= $req->queryParams();
		if (count($value)) {
			// $this->app->user()->pref($name, $value);
			$this->em->flush();
		} else {
			// $value = $this->app->user()->pref($name);
			if (isset($value)) {
				$req->queryParams($value);
			}
		}
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