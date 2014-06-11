<?php
namespace Ayre\Core\Controller;

use Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class Index extends Action
{
	public function index(Request $req, Response $res)
	{
		return $res->redirect($this->url([], 'content', true));
	}
	
	public function about(Request $req, Response $res)
	{}

	public function prefs(Request $req, Response $res)
	{
		foreach ($req->queryParams() as $name => $value) {
			$req->user->pref($name, $value);
		}
		$this->em->flush();
		return true;
	}

	public function publish(Request $req, Response $res)
	{
		$this->app->publish();

		return $res->redirect($this->url([], 'index', true));
	}
}