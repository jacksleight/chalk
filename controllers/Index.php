<?php
namespace Ayre\Controller;

use Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class Index extends Action
{
	public function index(Request $req, Response $res)
	{

	}

	public function publish(Request $req, Response $res)
	{
		$this->app->publish();

		return $res->redirect($this->url([], 'index', true));
	}
}