<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Chalk\Chalk,
	Chalk\Module\Standard,
    Coast\Request,
    Coast\Response;

class Core extends Standard
{
	public function chalk(Chalk $chalk)
	{
		$class = get_class($this);

        $chalk->em->dir($class, $this->dir('app/lib'));
		$chalk->view->dir($class, $this->dir('app/views/admin'));
        $chalk->controller->nspace($class, "{$class}\\Controller");
		
		$chalk
			->contentClass("{$class}\\Page")
			->contentClass("{$class}\\File");

		$chalk->frontend->handlers([
			"{$class}\\Page" => function(Request $req, Response $res) {
				$entity = Chalk::entity($req->content);
				return $res->html($this->parse($this->view->render('chalk/' . $entity->path, [
					'req'	=> $req,
					'res'	=> $res,
					'node'	=> $req->node,
					'page'	=> $req->content
		        ])));
			},
			"{$class}\\File" => function(Request $req, Response $res) {
		        $file = $req->content->file();
		        if (!$file->exists()) {
		            return false;
		        }
		        return $res->redirect($this->app->url->file($file));
			},
			"{$class}\\Alias" => function(Request $req, Response $res) {
		        $content = $req->content->content();
		        return $res->redirect($this->url($content));
			},
			"{$class}\\Url" => function(Request $req, Response $res) {
		        $url = $req->content->url();
		        return $res->redirect($url);
			},
			"{$class}\\Blank" => function(Request $req, Response $res) {
				return;
			},
		]);
	}
}