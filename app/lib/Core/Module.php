<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core;

use Chalk\Chalk,
	Chalk\Event,
	Chalk\Module as ChalkModule,
    Coast\Request,
    Coast\Response;

class Module extends ChalkModule
{
    public function __construct()
    {
        parent::__construct('../../../');
    }
    
	public function init()
	{
        $this
        	->emDir('app/lib')
			->viewDir('app/views')
        	->controllerNspace('Controller')
        	->controllerAll('All')
			->frontendViewDir()
        	->register('Event\ListWidgets')
        	->register('Event\ListContents')
        	->register('Event\ListSettings')
        	->listen($this->nspace('Event\ListSettings'), function(Event $event) {
        		$event->settings([
					[
						'label' => 'Users',
						'name'  => 'setting',
						'params'=> ['controller' => 'user'],
					], [
						'label' => 'Domains',
						'name'  => 'setting',
						'params'=> ['controller' => 'domain'],
					], [
						'label' => 'Structures',
						'name'  => 'setting',
						'params'=> ['controller' => 'setting_structure'],
					]
				]);
        	})
        	->listen($this->nspace('Event\ListContents'), function(Event $event) {
        		$event->contents([
	        		$this->nspace('Page'),
	        		$this->nspace('File'),
	        		$this->nspace('Block'),
	        		// $this->nspace('Alias'),
	        		// $this->nspace('Url'),
	        		// $this->nspace('Blank'),
        		]);
        	});

		$this->_chalk->frontend->handlers([
			'Chalk\Core\Page' => function(Request $req, Response $res) {
				$info = Chalk::info($req->chalk->content);
				return $res->html($this->parse($this->view->render($info->local->path, [
					'req'	=> $req,
					'res'	=> $res,
					'node'	=> $req->node,
					'page'	=> $req->chalk->content
		        ], 'Chalk\Core')));
			},
			'Chalk\Core\File' => function(Request $req, Response $res) {
		        $file = $req->chalk->content->file();
		        if (!$file->exists()) {
		            return false;
		        }
		        return $res->redirect($this->app->url->file($file));
			},
			'Chalk\Core\Alias' => function(Request $req, Response $res) {
		        $content = $req->chalk->content->content();
		        return $res->redirect($this->url($content));
			},
			'Chalk\Core\Url' => function(Request $req, Response $res) {
		        $url = $req->chalk->content->url();
		        return $res->redirect($url);
			},
			'Chalk\Core\Blank' => function(Request $req, Response $res) {
				return;
			},
		]);
	}
}