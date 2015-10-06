<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Backend;
use Chalk\App as Chalk;
use Chalk\Event;
use Chalk\Frontend;
use Chalk\InfoList;
use Chalk\Module as ChalkModule;
use Closure;
use Coast\Request;
use Coast\Response;
use Coast\Router;
use Chalk\Core\File\Listener as FileListener;
use Chalk\Core\Structure\Node\Listener as StructureNodeListener;
use Chalk\Core\Behaviour\Publishable\Listener as PublishableListener;
use Chalk\Core\Behaviour\Loggable\Listener as LoggableListener;
use Chalk\Core\Behaviour\Searchable\Listener as SearchableListener;
use Chalk\Core\Behaviour\Trackable\Listener as TrackableListener;
use Chalk\Core\Behaviour\Versionable\Listener as VersionableListener;

class Module extends ChalkModule
{
    public function __construct()
    {
        parent::__construct('core');
    }
    
    public function init()
    {
        $this
            ->entityDir($this->name())
            ->entityListener($this->name('file'), new FileListener())
            ->entityListener($this->name('structure_node'), new StructureNodeListener())
            ->entityListener($this->name('publishable'), new PublishableListener())
            ->entityListener($this->name('searchable'), new SearchableListener())
            // ->entityListener($this->name('loggable'), new LoggableListener())
            // ->entityListener($this->name('versionable'), new VersionableListener())
            ->entityListener($this->name('trackable'), $trackable = new TrackableListener());
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
                } catch (\Coast\Router\Exception $e) {}             
            })
            ->frontendUrlResolver($this->name('content'), function($content, $info) {
                try {
                    return $this->frontend->url
                        ->route([], $this->name("content_{$content['id']}"), true);
                } catch (\Coast\Router\Exception $e) {}
            })
            ->frontendUrlResolver($this->name('url'), function($url, $info) {
                return $url['url'];
            })
            ->frontendUrlResolver($this->name('file'), function($file, $info) {
                if (is_array($file)) {
                    // @todo Remove this once File works with array hydration
                    $file = $this->em($this->name('file'))->id($file['id']);
                }
                return $this->frontend->url
                    ->file($file['file']);
            });

        $this->frontendInitDomain();
        $this->frontendInitNodes();
    }

    public function frontendInitDomain()
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

    public function frontendInitNodes()
    {
        $conn  = $this->em->getConnection();
        $nodes = $conn->query("
            SELECT n.id,
                n.sort, n.left, n.right, n.depth,
                n.name, n.slug, n.path,
                n.structureId,
                n.parentId,
                c.id AS content_id,
                c.type AS content_type,
                c.data AS content_data
            FROM core_structure_node AS n
                INNER JOIN core_structure AS s ON s.id = n.structureId
                INNER JOIN core_domain__core_structure AS d ON d.core_structureId = s.id
                INNER JOIN core_content AS c ON c.id = n.contentId
            WHERE d.core_domainId = {$this->frontend->domain->id}
        ")->fetchAll();

        $nodeMap = [];
        foreach ($nodes as $node) {
            $content = [
                'id'   => $node['content_id'],
                'type' => $node['content_type'],
                'data' => json_decode($node['content_data'], true),
            ];
            if (!isset($content['id'])) {
                continue;
            }
            $info = Chalk::info($node['content_type']);
            $name = $info->local->name;
            if ($info->name == 'core_page' && isset($content['data']['delegate'])) {
                $delegate = $content['data']['delegate'] + [
                    'name'   => null,
                    'params' => [],
                ];
                $info   = Chalk::info($delegate['name']);
                $params = $delegate['params'];
            } else {
                $params = [];
            }
            $module = $this->app->module($info->module->name);
            if (!method_exists($module, 'core_frontendInitNode')) {
                throw new \Chalk\Exception("Module '{$info->module->name}' does not implment 'core_frontendInitNode'");
            }
            $params = [
                'group'   => $module->name(),
                'node'    => $node,
                'content' => $content,
                $name     => $content['id'],
            ] + $params;
            $primary = $module->core_frontendInitNode(
                $info->local->name,
                $node,
                $content,
                $params
            );
            if (!$primary) {
                throw new \Chalk\Exception("No primary route provided for '{$info->name}' on node '{$node['id']}'");
            }
            $this
                ->frontendRouteAlias(
                    $this->name("content_{$content['id']}"),
                    $primary
                )
                ->frontendRouteAlias(
                    $this->name("structure_node_{$node['id']}"),
                    $primary
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
            ->backendRoute(
                $this->name('index'),
                Router::METHOD_ALL,
                "{controller}?/{action}?/{id}?", [
                    'controller' => 'index',
                    'action'     => 'index',
                    'id'         => null,
                ])
            ->backendRoute(
                $this->name('about'),
                Router::METHOD_ALL,
                "about", [
                    'controller' => 'auth',
                    'action'     => 'about',
                ])
            ->backendRoute(
                $this->name('sandbox'),
                Router::METHOD_ALL,
                "sandbox", [
                    'controller' => 'index',
                    'action'     => 'sandbox',
                ])
            ->backendRoute(
                $this->name('passwordRequest'),
                Router::METHOD_ALL,
                "password-request", [
                    'controller' => 'auth',
                    'action'     => 'password-request',
                ])
            ->backendRoute(
                $this->name('passwordReset'),
                Router::METHOD_ALL,
                "password-reset/{token}", [
                    'controller' => 'auth',
                    'action'     => 'password-reset',
                ])
            ->backendRoute(
                $this->name('login'),
                Router::METHOD_ALL,
                "login", [
                    'controller' => 'auth',
                    'action'     => 'login',
                ])
            ->backendRoute(
                $this->name('logout'),
                Router::METHOD_ALL,
                "logout", [
                    'controller' => 'auth',
                    'action'     => 'logout',
                ])
            ->backendRoute(
                $this->name('profile'),
                Router::METHOD_ALL,
                "profile", [
                    'controller' => 'profile',
                    'action'     => 'edit',
                ])
            ->backendRoute(
                $this->name('prefs'),
                Router::METHOD_ALL,
                "prefs", [
                    'controller' => 'index',
                    'action'     => 'prefs',
                ])
            ->backendRoute(
                $this->name('content'),
                Router::METHOD_ALL,
                "content/{entity}?/{action}?/{content}?", [
                    'controller' => 'content',
                    'action'     => 'index',
                    'entity'     => null,
                    'content'    => null,
                ])
            ->backendRoute(
                $this->name('setting'),
                Router::METHOD_ALL,
                "setting/{controller}?/{action}?/{id}?", [
                    'controller' => 'setting',
                    'action'     => 'index',
                    'id'         => null,
                ])
            ->backendRoute(
                $this->name('widget'),
                Router::METHOD_ALL,
                "widget/{action}/{entity}", [
                    'controller' => 'widget',
                ])
            ->backendRoute(
                $this->name('structure'),
                Router::METHOD_ALL,
                "structure/{action}?/{structure}?", [
                    'controller' => 'structure',
                    'action'     => 'index',
                    'structure'  => null,
                ])
            ->backendRoute(
                $this->name('structure_node'),
                Router::METHOD_ALL,
                "structure/node/{structure}/{action}?/{node}?", [
                    'controller' => 'structure_node',
                    'action'     => 'index',
                    'node'       => null,
                ]);

        $this
            ->backendHookListen($this->name('contentList'), function(InfoList $list) {
                if ($list->filter() == $this->name('main')) {
                    return $list
                        ->item($this->name('page'), [])
                        ->item($this->name('file'), [])
                        ->item($this->name('url'), [])
                        ->item($this->name('alias'), [])
                        ->item($this->name('block'), []);
                } else if ($list->filter() == $this->name('node')) {
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
                    ], $this->name('setting'))
                    ->item($this->name('setting\tag'), [
                        'label' => 'Tags',
                        'icon'  => 'price-tag',
                        'url'   => [[
                            'controller' => 'setting_tag'
                        ], $this->name('setting')],
                    ], $this->name('setting'));
            });
    }

    public function core_frontendInitNode($name, $node, $content, $params)
    {
        switch ($name) {
            case 'page':
            case 'file':
            case 'url':
            case 'alias':
            case 'null':
                $primary = $this->name("{$name}_{$content['id']}");
                $this
                    ->frontendRoute(
                        $primary,
                        Router::METHOD_ALL,
                        "{$node['path']}",
                        $params + [
                            'controller' => "{$name}",
                            'action'     => 'index',
                        ]);
                return $primary;      
            break;
        }
    }

    public function publish()
    {
        // foreach (self::$_publishables as $class) {
           $entities = $this->em('core_content')->all(['isPublishable' => true]);
           // if (is_subclass_of($class, 'Chalk\Core\Behaviour\Versionable')) {
           //     $last = null;
           //     foreach ($entities as $entity) {
           //         $entity->status = $entity === $last
           //         ? Chalk::STATUS_ARCHIVED
           //         : Chalk::STATUS_PUBLISHED;
           //         $last = $entity;
           //     }
           // } else {
           //     foreach ($entities as $entity) {
           //         $entity->status = Chalk::STATUS_PUBLISHED;
           //     }
           // }
           foreach ($entities as $entity) {
               $entity->status = Chalk::STATUS_PUBLISHED;
           }
        // }
        $this->em->flush();
    }
}