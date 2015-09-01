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
        parent::__construct('core', '../../..');
    }
    
    public function init()
    {
        $this->em
            ->dir($this->nspace(), $this->dir('app/lib'));
    }
    
    public function initFrontend()
    {
        $this->frontend->controller
            ->nspace($this->name(), $this->nspace('Frontend\Controller'))
            ->all(['All', $this->name()]);

        $this->frontend->view
            ->dir($this->name(), $this->dir('frontend/views'));

        $this->frontend->url
            ->resolver($this->name('structure_node'), function($node) {
                try {
                    return $this->frontend->url
                        ->route([], "core_structure_node_{$node['id']}", true);
                } catch (\Coast\Router\Exception $e) {
                    return;
                }                    
            })
            ->resolver($this->name('content'), function($content) {
                try {
                    return $this->frontend->url
                        ->route([], "{$content['type']}_{$content['id']}", true);
                } catch (\Coast\Router\Exception $e) {
                    return;
                }
            })
            ->resolver($this->name('url'), function($url) {
                return $url['url'];
            })
            ->resolver($this->name('file'), function($file) {
                // @todo Remove this once File works with array hydration
                if (is_array($file)) {
                    $file = $this->em($this->name('file'))->id($file['id']);
                }
                return $this->frontend->url
                    ->file($file['file']);
            });

        $domain = $this->em('core_domain')->id(1);   
        
        $this->frontend->domain = $domain;
        $this->frontend->root   = $domain->structures->first()->root;
        $this->frontend->home   = $domain->structures->first()->root->content;
        
        $structures = [];
        foreach ($domain->structures as $structure) {
            $structures[$structure->id] = $structure;
        }
        $this->frontend->structures = $structures;

        $conn  = $this->em->getConnection();
        $nodes = $conn->query("
            SELECT n.id,
                n.sort, n.left, n.right, n.depth,
                n.name, n.slug, n.path,
                n.structureId,
                n.parentId,
                c.type AS content_type,
                n.contentId AS content_id
            FROM core_structure_node AS n
                INNER JOIN core_structure AS s ON s.id = n.structureId
                INNER JOIN core_domain__core_structure AS d ON d.core_structureId = s.id
                INNER JOIN core_content AS c ON c.id = n.contentId
            WHERE d.core_domainId = {$domain->id}
        ")->fetchAll();

        $this->map = [];
        foreach ($nodes as $node) {
            if (!isset($node['content_id'])) {
                continue;
            }
            $this->map[$node['id']] = $node;
            $info   = Chalk::info($node['content_type']);
            $module = $this->app->module($info->module->name);
            $params = [
                'info'       => $info,
                'node'       => $node,
                'content'    => $node['content_id'],
                'module'     => $this->name(),
                'controller' => $info->local->name,
                'action'     => 'index',
            ];
            $this->frontend->router
                ->route("core_structure_node_{$node['id']}", 'all', $node['path'], $params)
                ->route("{$node['content_type']}_{$node['content_id']}", 'all', $node['path'], $params);
        }
    }
    
    public function initBackend()
    {
        $this->backend->view
            ->dir($this->name(), $this->dir('backend/views'));
        
        $this->backend->controller
            ->nspace($this->name(), $this->nspace('Backend\Controller'))
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
                    ->item($this->name('setting\domain'), [
                        'label' => 'Site',
                        'url'   => [['controller' => 'setting_domain', 'action' => 'edit', 'id' => 1], 'setting'],
                    ], $this->name('setting'))
                    ->item($this->name('setting\user'), [
                        'label' => 'Users',
                        'url'   => [['controller' => 'setting_user'], 'setting'],
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
}