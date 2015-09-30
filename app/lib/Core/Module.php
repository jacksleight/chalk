<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core;

use Chalk\Backend;
use Chalk\Chalk;
use Chalk\Event;
use Chalk\Frontend;
use Chalk\InfoList;
use Chalk\Module as ChalkModule;
use Closure;
use Coast\Request;
use Coast\Response;
use Coast\Router;

class Module extends ChalkModule
{
    public function __construct()
    {
        parent::__construct('core', '../../..');
    }
    
    public function init()
    {
        $this
            ->entityDir($this->name(), 'app/lib');
    }
    
    public function frontendInit()
    {
        $this
            ->frontendControllerAll($this->name())
            ->frontendControllerNspace($this->name())
            ->frontendViewDir($this->name());

        $this
            ->frontendUrlResolver($this->name('structure_node'), function($node) {
                try {
                    return $this->frontend->url
                        ->route([], $this->name("structure_node_{$node['id']}"), true);
                } catch (\Coast\Router\Exception $e) {
                    return;
                }                    
            })
            ->frontendUrlResolver($this->name('content'), function($content) {
                try {
                    return $this->frontend->url
                        ->route([], $this->name("content_{$content['id']}"), true);
                } catch (\Coast\Router\Exception $e) {
                    return;
                }
            })
            ->frontendUrlResolver($this->name('url'), function($url) {
                return $url['url'];
            })
            ->frontendUrlResolver($this->name('file'), function($file) {
                // @todo Remove this once File works with array hydration
                if (is_array($file)) {
                    $file = $this->em($this->name('file'))->id($file['id']);
                }
                return $this->frontend->url
                    ->file($file['file']);
            });

        $domain = $this->em($this->name('domain'))->id(1);   
        
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

        $nodeMap = [];
        foreach ($nodes as $node) {
            if (!isset($node['content_id'])) {
                continue;
            }
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
            $this
                ->frontendRoute(
                    $this->name("structure_node_{$node['id']}"),
                    Router::METHOD_ALL,
                    $node['path'],
                    $params
                )
                ->frontendRoute(
                    $this->name("content_{$node['content_id']}"),
                    Router::METHOD_ALL,
                    $node['path'],
                    $params
                );
           $nodeMap[$node['id']] = $node;
        }
        $this->nodeMap = $nodeMap;
    }
    
    public function backendInit()
    {
        $this
            ->backendControllerAll($this->name())
            ->backendControllerNspace($this->name())
            ->backendViewDir($this->name());

        $this
            ->backendRoute($this->name('index'), Router::METHOD_ALL, "{controller}?/{action}?/{id}?", [
                'controller' => 'index',
                'action'     => 'index',
                'id'         => null,
            ])
            ->backendRoute($this->name('about'), Router::METHOD_ALL, "about", [
                'controller' => 'auth',
                'action'     => 'about',
            ])
            ->backendRoute($this->name('sandbox'), Router::METHOD_ALL, "sandbox", [
                'controller' => 'index',
                'action'     => 'sandbox',
            ])
            ->backendRoute($this->name('passwordRequest'), Router::METHOD_ALL, "password-request", [
                'controller' => 'auth',
                'action'     => 'password-request',
            ])
            ->backendRoute($this->name('passwordReset'), Router::METHOD_ALL, "password-reset/{token}", [
                'controller' => 'auth',
                'action'     => 'password-reset',
            ])
            ->backendRoute($this->name('login'), Router::METHOD_ALL, "login", [
                'controller' => 'auth',
                'action'     => 'login',
            ])
            ->backendRoute($this->name('logout'), Router::METHOD_ALL, "logout", [
                'controller' => 'auth',
                'action'     => 'logout',
            ])
            ->backendRoute($this->name('profile'), Router::METHOD_ALL, "profile", [
                'controller' => 'profile',
                'action'     => 'edit',
            ])
            ->backendRoute($this->name('prefs'), Router::METHOD_ALL, "prefs", [
                'controller' => 'index',
                'action'     => 'prefs',
            ])
            ->backendRoute($this->name('content'), Router::METHOD_ALL, "content/{entity}?/{action}?/{content}?", [
                'controller' => 'content',
                'action'     => 'index',
                'entity'     => null,
                'content'    => null,
            ])
            ->backendRoute($this->name('setting'), Router::METHOD_ALL, "setting/{controller}?/{action}?/{id}?", [
                'controller' => 'setting',
                'action'     => 'index',
                'id'         => null,
            ])
            ->backendRoute($this->name('widget'), Router::METHOD_ALL, "widget/{action}/{entity}", [
                'controller' => 'widget',
            ])
            ->backendRoute($this->name('structure'), Router::METHOD_ALL, "structure/{action}?/{structure}?", [
                'controller' => 'structure',
                'action'     => 'index',
                'structure'  => null,
            ])
            ->backendRoute($this->name('structure_node'), Router::METHOD_ALL, "structure/node/{structure}/{action}?/{node}?", [
                'controller' => 'structure_node',
                'action'     => 'index',
                'node'       => null,
            ]);

        $this
            ->backendHookListen($this->name('contentList'), function(InfoList $list) {
                if ($list->filter() == $this->name('node')) {
                    return $list
                        ->item($this->name('page'), [])
                        ->item($this->name('file'), [])
                        ->item($this->name('url'), [])
                        ->item($this->name('alias'), [])
                        ->item($this->name('blank'), []);
                } else if ($list->filter() == $this->name('link')) {
                    return $list
                        ->item($this->name('page'), [])
                        ->item($this->name('file'), [])
                        ->item($this->name('url'), [])
                        ->item($this->name('alias'), [])
                        ->item($this->name('blank'), []);
                } else if ($list->filter() == $this->name('image')) {
                    return $list
                        ->item($this->name('file'), [
                            'subtypes' => [
                                'image/gif',
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ],
                        ]);
                } else {
                    return $list
                        ->item($this->name('page'), [])
                        ->item($this->name('file'), [])
                        ->item($this->name('url'), [])
                        ->item($this->name('alias'), [])
                        ->item($this->name('block'), [])
                        ->item($this->name('blank'), []);
                }
            })
            ->backendHookListen($this->name('widgetList'), function(InfoList $list) {
                return $list;
            })
            ->backendHookListen($this->name('navList'), function(NavList $list) {
                return $list->item($this->name('primary'), [])
                    ->item($this->name('secondary'), [])
                    ->item($this->name('structure'), [
                        'label'      => 'Structures',
                        'icon-block' => 'structure',
                        'url'        => [[], $this->name('structure')],
                    ], $this->name('primary'))
                    ->item($this->name('content'), [
                        'label'      => 'Content',
                        'icon-block' => 'content',
                        'url'        => [[], $this->name('content')],
                    ], $this->name('primary'))
                    ->item($this->name('setting'), [
                        'label'      => 'Settings',
                        'icon-block' => 'settings',
                        'url'        => [[], $this->name('setting')],
                    ], $this->name('secondary'))
                    ->item($this->name('setting\domain'), [
                        'label' => 'Site',
                        'icon' => 'publish',
                        'url'   => [[
                            'controller' => 'setting_domain',
                            'action'     => 'edit',
                            'id'         => 1
                        ], $this->name('setting')],
                    ], $this->name('setting'))
                    ->item($this->name('setting\user'), [
                        'label' => 'Users',
                        'icon' => 'user',
                        'url'   => [[
                            'controller' => 'setting_user'
                        ], $this->name('setting')],
                    ], $this->name('setting'))
                    ->item($this->name('setting\structure'), [
                        'label' => 'Structures',
                        'icon' => 'structure',
                        'url'   => [[
                            'controller' => 'setting_structure'
                        ], $this->name('setting')],
                    ], $this->name('setting'));
            });
    }
}