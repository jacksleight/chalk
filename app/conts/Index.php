<?php
namespace Chalk\Core\Controller;

use Coast\App\Controller\Action,
	Coast\Request,
	Coast\Response;

class Index extends Action
{
	public function index(Request $req, Response $res)
	{
		$contents = $this->app->fire('Chalk\Core\Event\ListContents')->contents();
		return $res->redirect($this->url([
			'info' => \Chalk\Chalk::info($contents[0])->name,
		], 'structure', true));
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