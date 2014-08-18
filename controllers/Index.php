<?php
namespace Ayre\Core\Controller;

use Coast\Controller\Action,
	Coast\Request,
	Coast\Response;

class Index extends Action
{
	public function index(Request $req, Response $res)
	{
		$contentClasses = $this->app->contentClasses();
		return $res->redirect($this->url([
			'entity' => \Ayre::entity($contentClasses[0])->name,
		], 'content', true));
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