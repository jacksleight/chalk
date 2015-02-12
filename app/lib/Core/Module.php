<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core;

use Chalk\Chalk,
	Chalk\Event,
	Chalk\Module as CoreModule,
    Coast\Request,
    Coast\Response;

class Module extends CoreModule
{
    public function __construct()
    {
        parent::__construct('../../../');
    }
    
	public function init(Chalk $chalk)
	{
		$chalk->frontend->view->dir('Chalk\Core', $chalk->config->viewDir->dir('core'));

        $chalk->em->dir('Chalk\Core', $this->dir('app/lib'));
		$chalk->view->dir('Chalk\Core', $this->dir('app/views'));
        $chalk->controller->nspace('Chalk\Core', 'Chalk\Core\Controller');
        $chalk->controller->all(['All', 'Chalk\Core']);
		
        $chalk
        	->register('Chalk\Core\Event\ListWidgets')
        	->register('Chalk\Core\Event\ListContents')
        	->register('Chalk\Core\Event\ListSettings')
        	->listen('Chalk\Core\Event\ListSettings', function(Event $event) {
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
        	->listen('Chalk\Core\Event\ListContents', function(Event $event) {
        		$event->contents([
	        		'Chalk\Core\Page',
	        		'Chalk\Core\File',
	        		'Chalk\Core\Block',
	        		// 'Chalk\Core\Alias',
	        		// 'Chalk\Core\Url',
	        		// 'Chalk\Core\Blank',
        		]);
        	});

		$chalk->frontend->handlers([
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
		        return $res->redirect($this->url->content($content->id));
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