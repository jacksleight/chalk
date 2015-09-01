<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core;

use Chalk\Chalk,
	Chalk\Event,
    Chalk\Backend,
    Chalk\Frontend,
	Chalk\Module as ChalkModule,
    Coast\Request,
    Coast\Response;

class Module extends ChalkModule
{
    public function __construct()
    {
        parent::__construct('core', '../../../');
    }
    
    public function init()
    {
        $this->em->dir($this->nspace(), $this->dir('app/lib'));

        $this->app->register($this->name('navigation'), $this->nspace('Event\Navigation'));
        $this->app->register($this->name('listWidgets'), $this->nspace('Event\ListWidgets'));
        $this->app->register($this->name('listContents'), $this->nspace('Event\ListContents'));
 
        $this->app->listen($this->name('navigation'), [$this, 'navigation']);
        $this->app->listen($this->name('listContents'), [$this, 'contents']);
    }
    
    public function initFrontend()
    {
        $this->frontend->url->resolver($this->name('url'), function($content) {
            return $content->url;
        });
        $this->frontend->url->resolver($this->name('file'), function($content) {
            return $this->file($content->file);
        });

        $this->frontend->view->dir($this->name(), $this->config->viewDir->dir(Chalk::info($this->nspace())->name));

        $this->frontend->handlers([
            'Chalk\Core\Page' => function(Request $req, Response $res) {
                $info = Chalk::info($req->chalk->content);
                return $res->html($this->parse($this->view->render($info->local->path, [
                    'req'   => $req,
                    'res'   => $res,
                    'node'  => $req->node,
                    'page'  => $req->chalk->content
                ], 'core')));
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
    
    public function initBackend()
    {
        $this->backend->view->dir($this->name(), $this->dir('app/views'));
        
        $this->backend->controller->nspace($this->name(), $this->nspace('Controller'));
        $this->backend->controller->all(['All', $this->name()]);
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

	public function contents(Event $event)
	{		
		$classes = [
    		'Page',
    		'File',
    		'Block',
    		'Alias',
    		'Url',
    		'Blank',
		];
		foreach ($classes as $class) {
			$event->content($this->nspace($class));
		}
	}
}