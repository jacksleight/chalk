<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Coast\Controller\Action,
	Coast\Request,
	Coast\Response;

class Index extends Action
{
	public function index(Request $req, Response $res)
	{
		$items = $this->navList->children('core_primary');
		if (count($items)) {
			$item = current($items);
			return $res->redirect($this->url($item['url'][0], $item['url'][1], true));
		}
	}
	
	public function about(Request $req, Response $res)
	{}
	
	public function sandbox(Request $req, Response $res)
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
		$this->chalk->module('core')->publish();

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

	public function select(Request $req, Response $res)
	{
		$wrap = $this->em->wrap($index = new \Chalk\Core\Model\Index());
		$wrap->graphFromArray($req->queryParams());
		$req->view->index = $wrap;

        if (!$req->isPost()) {
            return;
        }

		$wrap->graphFromArray($req->bodyParams());
		$contents = [];
		foreach ($index->contents as $content) {
			$contents[] = [
				'id'	=> $content->master->id,
				'name'	=> $content->master->name,
				'card'	=> $this->view->render('content/card', ['content' => $content], 'core')->toString(),
			];
		}
		return $res->json(['contents' => $contents]);
	}

	public function forbidden(Request $req, Response $res)
	{
        return $res
            ->status(403)
            ->html($this->view->render('error/forbidden', ['req' => $req, 'res' => $res]));
	}
}