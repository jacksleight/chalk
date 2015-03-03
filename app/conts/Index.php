<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

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

		$this->notify("Content was published successfully", 'positive');
		if (isset($req->redirect)) {
			return $res->redirect($req->redirect);
		} else {
			return $res->redirect($this->url([], 'index', true));
		}		
	}

	public function source(Request $req, Response $res)
	{
	    $req->view->source = $wrap = $this->em->wrap(
            $source = new \Chalk\Core\Model\Source()
        );

	    $wrap->graphFromArray($req->bodyParams());
        if (!$req->post) {
            return;
        }

		return $res->json([
			'code' => $source->codeRaw,
		]);
	}

	public function forbidden(Request $req, Response $res)
	{
        return $res
            ->status(403)
            ->html($this->view->render('error/forbidden', ['req' => $req, 'res' => $res]));
	}
}