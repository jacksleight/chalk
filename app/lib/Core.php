<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Chalk\Chalk,
	Chalk\Event,
	Chalk\Module\Standard,
    Coast\Request,
    Coast\Response;

class Core extends Standard
{
	public function chalk(Chalk $chalk)
	{
        $chalk->em->dir('Chalk\Core', $this->dir('app/lib'));
		$chalk->view->dir('Chalk\Core', $this->dir('app/views/admin'));
        $chalk->controller->nspace('Chalk\Core', 'Chalk\Core\Controller');
		
        $chalk
        	->register('Chalk\Core\Event\ListWidgets')
        	->register('Chalk\Core\Event\ListContents')
        	->listen('Chalk\Core\Event\ListContents', function(Event $event) {
        		$event->contents([
	        		'Chalk\Core\Page',
	        		'Chalk\Core\File',
        		]);
        	});

		$chalk->frontend->handlers([
			'Chalk\Core\Page' => function(Request $req, Response $res) {
				$entity = Chalk::entity($req->content);
				return $res->html($this->parse($this->view->render('chalk/' . $entity->path, [
					'req'	=> $req,
					'res'	=> $res,
					'node'	=> $req->node,
					'page'	=> $req->content
		        ])));
			},
			'Chalk\Core\File' => function(Request $req, Response $res) {
		        $file = $req->content->file();
		        if (!$file->exists()) {
		            return false;
		        }
		        return $res->redirect($this->app->url->file($file));
			},
			'Chalk\Core\Alias' => function(Request $req, Response $res) {
		        $content = $req->content->content();
		        return $res->redirect($this->url($content));
			},
			'Chalk\Core\Url' => function(Request $req, Response $res) {
		        $url = $req->content->url();
		        return $res->redirect($url);
			},
			'Chalk\Core\Blank' => function(Request $req, Response $res) {
				return;
			},
		]);
	}
}