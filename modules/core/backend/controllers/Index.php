<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Coast\Controller\Action;
use Coast\Request;
use Coast\Response;

class Index extends Action
{
	public function index(Request $req, Response $res)
	{
		$items = $this->navList->children(0);
		if (!count($items)) {
            throw new \Exception('No route for redirection');
        }
		$item = current($items);
		return $res->redirect($item['url']);
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
            $source = new \Chalk\Core\Backend\Model\Index\Source()
        );

	    $wrap->graphFromArray($req->bodyParams());
        if (!$req->post) {
            return;
        }

		$req->data->code = $source->codeRaw;
	}

	public function select(Request $req, Response $res)
	{
        $filters = $this->chalk->module('core')->contentList($req->filters);
        $info = isset($req->type)
            ? Chalk::info($req->type)
            : $filters->first();
        $req->queryParam('type', $info->name);

        $class = "\\{$info->module->class}\\Model\\{$info->local->class}\\Index";
        if (!class_exists($class)) {
            $class = "\Chalk\Core\Backend\Model\Content\Index";
        }
        $index = new $class();

		$wrap = $this->em->wrap($index);
		$wrap->graphFromArray($req->queryParams());
		$req->view->index   = $wrap;
		$req->view->filters = $filters;

        if (!$req->isPost() && !$index->contentNew) {
            return;
        }

		$wrap->graphFromArray($req->bodyParams());
		$contents = [];
		foreach ($index->contents as $content) {
			$contents[] = [
				'id'	=> $content->id,
				'name'	=> $content->name,
				'card'	=> $this->view->render('content/card', [
					'content' => $content,
				], 'core')->toString(),
			];
		}
		$req->data->contents = $contents;
	}

	public function forbidden(Request $req, Response $res)
	{
        return $res
            ->status(403)
            ->html($this->view->render('error/forbidden', [
            	'req' => $req,
            	'res' => $res,
            ], 'core'));
	}
	
	public function ping(Request $req, Response $res)
	{
		return true;
	}
}