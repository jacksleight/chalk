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
        	->register('Event\Navigation')
        	->register('Event\ListWidgets')
        	->listen($this->nspace('Event\Navigation'), [$this, 'navigation']);

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

	public function navigation(Event $event, Request $req)
	{
		$event
			->item($this->nspace('Primary'), [])
			->item($this->nspace('Secondary'), []);

		$event
			->item($this->nspace('Structure'), [
				'label'	=> 'Structure',
				'icon'	=> 'structure',
				'url'	=> [[], 'structure'],
			], $this->nspace('Primary'))
			->item($this->nspace('Content'), [
				'label'	=> 'Content',
				'icon'	=> 'content',
				'url'	=> [[], 'content'],
			], $this->nspace('Primary'));

		if ($req->user->isAdministrator()) {
			$event
				->item($this->nspace('Setting'), [
					'label'	=> 'Settings',
					'icon'	=> 'settings',
					'url'	=> [[], 'setting'],
				], $this->nspace('Secondary'));
		}
		
		$classes = [
    		'Page',
    		'File',
    		'Block',
    		'Alias',
    		'Url',
    		'Blank',
		];
		foreach ($classes as $class) {
			$info = Chalk::info($this->nspace($class));
			$event
				->item($this->nspace("Content\\{$info->local->class}"), [
					'label'	=> $info->plural,
					'url'	=> [['entity' => $info->name], 'content'],
				], $this->nspace('Content'));
		}

		$event
			->item($this->nspace('Setting\User'), [
				'label'	=> 'Users',
				'url'	=> [['controller' => 'setting_user'], 'setting'],
			], $this->nspace('Setting'))
			->item($this->nspace('Setting\Domain'), [
				'label'	=> 'Domains',
				'url'	=> [['controller' => 'setting_domain'], 'setting'],
			], $this->nspace('Setting'))
			->item($this->nspace('Setting\Structure'), [
				'label'	=> 'Structures',
				'url'	=> [['controller' => 'setting_structure'], 'setting'],
			], $this->nspace('Setting'));
	}
}