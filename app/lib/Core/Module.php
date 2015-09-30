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
            ->frontendUrlResolver($this->name('structure_node'), function($node, $info) {
                try {
                    return $this->frontend->url
                        ->route([], $this->name("structure_node_{$node['id']}"), true);
                } catch (\Coast\Router\Exception $e) {
                    return;
                }                    
            })
            ->frontendUrlResolver($this->name('content'), function($content, $info) {
                try {
                    return $this->frontend->url
                        ->route([], "{$info->name}_{$content['id']}", true);
                } catch (\Coast\Router\Exception $e) {
                    return;
                }
            })
            ->frontendUrlResolver($this->name('url'), function($url, $info) {
                return $url['url'];
            })
            ->frontendUrlResolver($this->name('file'), function($file, $info) {
                // @todo Remove this once File works with array hydration
                if (is_array($file)) {
                    $file = $this->em($this->name('file'))->id($file['id']);
                }
                return $this->frontend->url
                    ->file($file['file']);
            });

        $this->_frontendInitDomain();
        $this->_frontendInitRoutes();
    }

    protected function _frontendInitDomain()
    {
        $domain = $this->em($this->name('domain'))->id(1);   
        
        $this->frontend->domain = $domain;
        $this->frontend->root   = $domain->structures->first()->root;
        $this->frontend->home   = $domain->structures->first()->root->content;
        
        $structures = [];
        foreach ($domain->structures as $structure) {
            $structures[$structure->id] = $structure;
        }
        $this->frontend->structures = $structures;
    }

    protected function _frontendInitRoutes()
    {
        $conn  = $this->em->getConnection();
        $nodes = $conn->query("
            SELECT n.id,
                n.sort, n.left, n.right, n.depth,
                n.name, n.slug, n.path,
                n.structureId,
                n.parentId,
                c.type AS content_type,
                c.data AS content_data,
                n.contentId AS content_id
            FROM core_structure_node AS n
                INNER JOIN core_structure AS s ON s.id = n.structureId
                INNER JOIN core_domain__core_structure AS d ON d.core_structureId = s.id
                INNER JOIN core_content AS c ON c.id = n.contentId
            WHERE d.core_domainId = {$this->frontend->domain->id}
        ")->fetchAll();

        $nodeMap = [];
        foreach ($nodes as $node) {
            if (!isset($node['content_id'])) {
                continue;
            }
            $params    = [];
            $info      = Chalk::info($node['content_type']);
            $data      = json_decode($node['content_data'], true);
            $module    = $this->app->module($info->module->name);
            $routeInfo = $info;
            if (isset($data['delegate'])) {
                $routeInfo = Chalk::info($data['delegate']['name']);
                $module    = $this->app->module($routeInfo->module->name);
                $params    = isset($data['delegate']['params'])
                    ? $data['delegate']['params']
                    : [];
            }
            if (!method_exists($module, 'core_frontendRoutes')) {
                continue;
            }
            $routes = $module->core_frontendRoutes($routeInfo->local->name, $node);
            if (!$routes) {
                throw new \Chalk\Exception("No routes were provided for '{$routeInfo->name}' on node '{$node['id']}'");
            }
            $i = 0;
            foreach ($routes as $name => $route) {
                $route = $route + ['all', '', [], null];
                $name = $name
                    ? $module->name("{$info->local->name}_{$node['content_id']}_{$name}")
                    : $module->name("{$info->local->name}_{$node['content_id']}");
                $route[1] = $route[1]
                    ? "{$node['path']}/{$route[1]}"
                    : "{$node['path']}";
                $route[2] = [
                    'info'    => $info,
                    'node'    => $node,
                    'data'    => $data,
                    'content' => $node['content_id'],
                    'module'  => $module->name(),
                ] + $route[2] + $params;
                if (!$i) {
                    $this->frontendRoute(
                        $this->name("structure_node_{$node['id']}"),
                        $route[0],
                        $route[1],
                        $route[2],
                        $route[3]
                    );
                }
                $this->frontendRoute(
                    $name,
                    $route[0],
                    $route[1],
                    $route[2],
                    $route[3]
                );
                $i++;
            }
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
                        ->item($this->name('alias'), []);
                } else if ($list->filter() == $this->name('link')) {
                    return $list
                        ->item($this->name('page'), [])
                        ->item($this->name('file'), [])
                        ->item($this->name('url'), [])
                        ->item($this->name('alias'), []);
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
                        ->item($this->name('block'), []);
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

    public function core_frontendRoutes($name, $node)
    {
        if ($name == 'alias') {
            return [
                '' => [Router::METHOD_ALL, '', [
                    'controller' => 'alias',
                    'action'     => 'index',
                ]],
            ];
        } else if ($name == 'file') {
            return [
                '' => [Router::METHOD_ALL, '', [
                    'controller' => 'file',
                    'action'     => 'index',
                ]],
            ];
        } else if ($name == 'null') {
            return [
                '' => [Router::METHOD_ALL, '', [
                    'controller' => 'null',
                    'action'     => 'index',
                ]],
            ];
        } else if ($name == 'page') {
            return [
                '' => [Router::METHOD_ALL, '', [
                    'controller' => 'page',
                    'action'     => 'index',
                ]],
            ];
        } else if ($name == 'url') {
            return [
                '' => [Router::METHOD_ALL, '', [
                    'controller' => 'url',
                    'action'     => 'index',
                ]],
            ];
        }
    }
}