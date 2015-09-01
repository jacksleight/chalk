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
        $this->em
            ->dir($this->nspace(), $this->dir('app/lib'));
    }
    
    public function initFrontend()
    {
        $this->frontend->url
            ->resolver($this->name('content'), function($content) {
                return $this->route([], "{$content['type']}_{$content['id']}");
            })
            ->resolver($this->name('url'), function($content) {
                return $content['url'];
            })
            ->resolver($this->name('file'), function($content) {
                return $this->file($content['file']);
            });

        $this->frontend->view
            ->dir($this->name(), $this->config->viewDir->dir(Chalk::info($this->nspace())->name));

        $domain = $this->em('core_domain')->one();   
        
        // $req->chalk = (object) [];       
        // $req->chalk->domain     = $domain;
        // $req->chalk->root       = $domain->structures->first()->root;
        // $req->chalk->home       = $domain->structures->first()->root->content;
        // $req->chalk->structures = [];
        // foreach ($domain->structures as $structure) {
        //     $req->chalk->structures[$structure->id] = $structure;
        // }

        $conn  = $this->em->getConnection();
        $nodes = $conn->query("
            SELECT n.id,
                n.sort, n.left, n.right, n.depth,
                n.name, n.slug, n.path,
                n.structureId,
                n.parentId,
                c.type AS contentType,
                n.contentId
            FROM core_structure_node AS n
                INNER JOIN core_structure AS s ON s.id = n.structureId
                INNER JOIN core_domain__core_structure AS d ON d.core_structureId = s.id
                INNER JOIN core_content AS c ON c.id = n.contentId
            WHERE d.core_domainId = {$domain->id}
        ")->fetchAll();
        $map = [];
        foreach ($nodes as $node) {
            if (!isset($node['contentId'])) {
                continue;
            }
            $info   = Chalk::info($node['contentType']);
            $module = $this->app->module($info->module->name);


            $routes = $module->routeNode($node);

            foreach ($routes as $key => $params) {
                // $name = $key
                //     ? "core_structure_node_{$node['id']}_{$key}"
                //     : "core_structure_node_{$node['id']}";
                // $this->frontend->router
                //     ->route($name, 'all', $node['path'], $params);
                $name = $key
                    ? "{$node['contentType']}_{$node['contentId']}_{$key}"
                    : "{$node['contentType']}_{$node['contentId']}";
                $this->frontend->router
                    ->route($name, 'all', $node['path'], $params);
            }



        }


















        // $this->frontend->handlers([
        //     'Chalk\Core\Page' => function(Request $req, Response $res) {
        //         $info = Chalk::info($req->chalk->content);
        //         return $res->html($this->parse($this->view->render($info->local->path, [
        //             'req'   => $req,
        //             'res'   => $res,
        //             'node'  => $req->node,
        //             'page'  => $req->chalk->content
        //         ], 'core')));
        //     },
        //     'Chalk\Core\File' => function(Request $req, Response $res) {
        //         $file = $req->chalk->content->file();
        //         if (!$file->exists()) {
        //             return false;
        //         }
        //         return $res->redirect($this->app->url->file($file));
        //     },
        //     'Chalk\Core\Alias' => function(Request $req, Response $res) {
        //         $content = $req->chalk->content->content();
        //         return $res->redirect($this->url->content($content->id));
        //     },
        //     'Chalk\Core\Url' => function(Request $req, Response $res) {
        //         $url = $req->chalk->content->url();
        //         return $res->redirect($url);
        //     },
        //     'Chalk\Core\Blank' => function(Request $req, Response $res) {
        //         return;
        //     },
        // ]);
    }
    
    public function initBackend()
    {
        $this->backend->view
            ->dir($this->name(), $this->dir('app/views'));
        
        $this->backend->controller
            ->nspace($this->name(), $this->nspace('Controller'))
            ->all(['All', $this->name()]);

        $this->backend->router->routes([
            'index' => ['all', "{controller}?/{action}?/{id}?", [
                'controller' => 'index',
                'action'     => 'index',
                'id'         => null,
            ]],
            'about' => ['all', "about", [
                'controller' => 'auth',
                'action'     => 'about',
            ]],
            'sandbox' => ['all', "sandbox", [
                'controller' => 'index',
                'action'     => 'sandbox',
            ]],
            'passwordRequest' => ['all', "password-request", [
                'controller' => 'auth',
                'action'     => 'password-request',
            ]],
            'passwordReset' => ['all', "password-reset/{token}", [
                'controller' => 'auth',
                'action'     => 'password-reset',
            ]],
            'login' => ['all', "login", [
                'controller' => 'auth',
                'action'     => 'login',
            ]],
            'logout' => ['all', "logout", [
                'controller' => 'auth',
                'action'     => 'logout',
            ]],
            'profile' => ['all', "profile", [
                'controller' => 'profile',
                'action'     => 'edit',
            ]],
            'prefs' => ['all', "prefs", [
                'controller' => 'index',
                'action'     => 'prefs',
            ]],
            'content' => ['all', "content/{entity}?/{action}?/{content}?", [
                'controller' => 'content',
                'action'     => 'index',
                'entity'     => null,
                'content'    => null,
            ]],
            'setting' => ['all', "setting/{controller}?/{action}?/{id}?", [
                'controller' => 'setting',
                'action'     => 'index',
                'id'         => null,
            ]],
            'widget' => ['all', "widget/{action}/{entity}", [
                'controller' => 'widget',
            ]],
            'structure' => ['all', "structure/{action}?/{structure}?", [
                'controller' => 'structure',
                'action'     => 'index',
                'structure'  => null,
            ]],
            'structure_node' => ['all', "structure/node/{structure}/{action}?/{node}?", [
                'controller' => 'structure_node',
                'action'     => 'index',
                'node'       => null,
            ]],
        ]);

        $this->backend->event
            ->register($this->name('navigation'), $this->nspace('Event\Navigation'))
            ->register($this->name('listWidgets'), $this->nspace('Event\ListWidgets'))
            ->register($this->name('listContents'), $this->nspace('Event\ListContents'))
            ->listen($this->name('navigation'), function(Event $event, Request $req) {
                $event
                    ->item($this->name('primary'), [])
                    ->item($this->name('secondary'), []);
                $event
                    ->item($this->name('structure'), [
                        'label' => 'Structure',
                        'icon'  => 'structure',
                        'url'   => [[], 'structure'],
                    ], $this->name('primary'))
                    ->item($this->name('content'), [
                        'label' => 'Content',
                        'icon'  => 'content',
                        'url'   => [[], 'content'],
                    ], $this->name('primary'));
                if ($req->user->isAdministrator()) {
                    $event
                        ->item($this->name('setting'), [
                            'label' => 'Settings',
                            'icon'  => 'settings',
                            'url'   => [[], 'setting'],
                        ], $this->name('secondary'));
                }
                $event
                    ->item($this->name('setting\user'), [
                        'label' => 'Users',
                        'url'   => [['controller' => 'setting_user'], 'setting'],
                    ], $this->name('setting'))
                    ->item($this->name('setting\domain'), [
                        'label' => 'Domains',
                        'url'   => [['controller' => 'setting_domain'], 'setting'],
                    ], $this->name('setting'))
                    ->item($this->name('setting\structure'), [
                        'label' => 'Structures',
                        'url'   => [['controller' => 'setting_structure'], 'setting'],
                    ], $this->name('setting'));
            })
            ->listen($this->name('listContents'), function(Event $event) {       
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
            });
    }

    public function routeNode($node)
    {
        return [
            [
                'node'    => $node,
                'content' => [$node['contentType'], $node['contentId']],
            ],
            'potato' => [
                'node'    => $node,
                'content' => [$node['contentType'], $node['contentId']],
            ],
        ];

    }
}