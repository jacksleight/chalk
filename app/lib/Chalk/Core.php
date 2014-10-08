<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Chalk,
	Chalk\Module\Standard,
    Coast\Request,
    Coast\Response;

class Core extends Standard
{
	public function chalk(Chalk $chalk)
	{
		$entity = Chalk::entity($this);

        $chalk->em->dir($entity->name, $this->dir('app/lib'));
		$chalk->view->dir($entity->name, $this->dir('app/views/admin'));
        $chalk->controller->nspace($entity->name, "{$entity->class}\\Controller");
		
		$chalk
			->contentClass("{$entity->class}\\Page")
			->contentClass("{$entity->class}\\File");

		$chalk->frontend->handlers([
			"{$entity->name}_page" => function(Request $req, Response $res) {
				$entity = Chalk::entity($req->content);
				return $res->html($this->parse($this->view->render('chalk/' . $entity->path, [
					'req'	=> $req,
					'res'	=> $res,
					'node'	=> $req->node,
					'page'	=> $req->content
		        ])));
			},
			"{$entity->name}_file" => function(Request $req, Response $res) {
		        $file = $req->content->file();
		        if (!$file->exists()) {
		            return false;
		        }
		        return $res->redirect($this->app->url->file($file));
			},
			"{$entity->name}_alias" => function(Request $req, Response $res) {
		        $content = $req->content->content();
		        return $res->redirect($this->url($content));
			},
			"{$entity->name}_url" => function(Request $req, Response $res) {
		        $url = $req->content->url();
		        return $res->redirect($url);
			},
			"{$entity->name}_blank" => function(Request $req, Response $res) {
				return;
			},
		]);
	}
}