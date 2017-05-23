<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Chalk;
use Chalk\Backend;
use Chalk\Core\Behaviour\Publishable\Listener as PublishableListener;
use Chalk\Core\Behaviour\Searchable\Listener as SearchableListener;
use Chalk\Core\Behaviour\Trackable\Listener as TrackableListener;
use Chalk\Core\File\Listener as FileListener;
use Chalk\Core\Parser\Plugin\Backend as PluginBackend;
use Chalk\Core\Parser\Plugin\Frontend as PluginFrontend;
use Chalk\Core\Parser\Plugin\StripEmpty;
use Chalk\Core\Structure\Node\Listener as StructureNodeListener;
use Chalk\Event;
use Chalk\Frontend;
use Chalk\InfoList;
use Chalk\Module as ChalkModule;
use Chalk\Parser;
use Chalk\Repository;
use Closure;
use Coast\Request;
use Coast\Response;
use Coast\Router;
use Coast\Sitemap;
use DOMDocument;
use DOMXPath;

class Module extends ChalkModule
{
    const VERSION = Chalk::VERSION;
    
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
            ->entityListener($this->name('trackable'), $trackable = new TrackableListener());
    }
    
    public function frontendInit()
    {
        $this
            ->frontendControllerAll($this->name())
            ->frontendControllerNspace($this->name())
            ->frontendViewDir($this->name());

        $this
            ->frontendParserPlugin($this->name('frontend'), new PluginFrontend())
            ->frontendParserPlugin($this->name('stripEmpty'), new StripEmpty([
                'tags' => ['p'],
            ]));

        $this
            ->frontendResolver($this->name('domain'), function($domain, $info) {
                return $this->frontend->url(); 
            })
            ->frontendResolver($this->name('structure_node'), function($node, $info) {
                try {
                    return $this->frontend->url->route([], $this->name("structure_node_{$node['id']}"), true);   
                } catch (\Coast\Router\Exception $e) {}             
            })
            ->frontendResolver($this->name('content'), function($content, $info) {
                try {
                    return $this->frontend->url->route([], $this->name("content_{$content['id']}"), true);
                } catch (\Coast\Router\Exception $e) {}
            })
            ->frontendResolver($this->name('url'), function($url, $info) {
                return $url['url'];
            })
            ->frontendResolver($this->name('file'), function($file, $info) {
                if (is_array($file)) {
                    // @todo Remove this once File works with array hydration
                    $file = $this->em($this->name('file'))->id($file['id']);
                }
                return $this->frontend->url
                    ->file($file['file']);
            });

        if ($this->app->isHttp()) {
            $this->frontendInitDomain();
            $this->frontendInitNodes();
        }

        $this
            ->frontendRoute(
                $this->name('robots'),
                Router::METHOD_ALL,
                "robots.txt", [
                    'group'      => $this->name(),
                    'controller' => 'index',
                    'action'     => 'robots',
                ]);

        if ($sitemap = $this->app->module('Chalk\Sitemap')) {
            $this
                ->frontendHookListen($sitemap->name('xml'), function(Sitemap $sitemap) {
                    foreach ($this->frontend->domain['structures'] as $structure) {
                        $nodes = $this->frontend->children($structure['nodes'][0], true);
                        foreach ($nodes as $node) {
                            $content = $node['content'];
                            if (isset($content['data']['delegate'])) {
                                continue;
                            }
                            $sitemap->urls[] = (new Sitemap\Url())->fromArray([
                                'url'        => $this->frontend->url($node),
                                'modifyDate' => $content['modifyDate'],
                            ]);
                        }
                    }
                    return $sitemap;
                });
        }
    }

    public function frontendInitDomain()
    {
        $domain = $this->em($this->name('domain'))->id(1, [], [
            'hydrate' => Repository::HYDRATE_ARRAY,
        ]);
        $structures = $this->em($this->name('structure'))->all([], [
            'hydrate' => Repository::HYDRATE_ARRAY,
        ]);
        
        $this->frontend->domain = $domain;
        $this->frontend->root   = $domain['structures'][0]['nodes'][0];
        $this->frontend->home   = $domain['structures'][0]['nodes'][0]['content'];
        
        $temp = [];
        foreach ($structures as $structure) {
            $structure['root']        = $structure['nodes'][0];
            $temp[$structure['id']]   = $structure;
            $temp[$structure['slug']] = $structure;
        }
        $this->frontend->structures = $temp;
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
                LEFT JOIN core_content AS c ON c.id = n.contentId
            WHERE d.core_domainId = {$this->frontend->domain['id']}
        ")->fetchAll();

        $nodeMap = [];
        foreach ($nodes as $node) {
            $nodeMap[$node['id']] = $node;
            $content = [
                'id'   => $node['content_id'],
                'type' => $node['content_type'],
                'data' => json_decode($node['content_data'], true),
            ];
            if (!isset($content['id'])) {
                continue;
            }
            if (isset($content['data']['delegate'])) {
                $delegate = $content['data']['delegate'] + [
                    'name'   => null,
                    'params' => [],
                ];
                $info   = Chalk::info($delegate['name']);
                $name   = 'content';
                $params = $delegate['params'];
            } else {
                $info   = Chalk::info($node['content_type']);
                $name   = $info->local->name;
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
        }
        $this->frontend->nodeMap = $nodeMap;
    }
    
    public function backendInit()
    {
        $this
            ->backendControllerAll($this->name())
            ->backendControllerNspace($this->name())
            ->backendViewDir($this->name());

        $this
            ->backendParserPlugin($this->name('backend'), new PluginBackend());

        $this
            ->backendRoute(
                $this->name('null'),
                Router::METHOD_ALL,
                "", [
                    'group'      => $this->name(),
                    'controller' => 'index',
                    'action'     => 'index',
                ])
            ->backendRoute(
                $this->name('index'),
                Router::METHOD_ALL,
                $this->path("{controller}?/{action}?/{id}?"), [
                    'group'      => $this->name(),
                    'controller' => 'index',
                    'action'     => 'index',
                    'id'         => null,
                ])
            ->backendRoute(
                $this->name('login'),
                Router::METHOD_ALL,
                $this->path("login"), [
                    'group'      => $this->name(),
                    'controller' => 'auth',
                    'action'     => 'login',
                ])
            ->backendRoute(
                $this->name('logout'),
                Router::METHOD_ALL,
                $this->path("logout"), [
                    'group'      => $this->name(),
                    'controller' => 'auth',
                    'action'     => 'logout',
                ])
            ->backendRoute(
                $this->name('passwordRequest'),
                Router::METHOD_ALL,
                $this->path("password-request"), [
                    'group'      => $this->name(),
                    'controller' => 'auth',
                    'action'     => 'password-request',
                ])
            ->backendRoute(
                $this->name('passwordReset'),
                Router::METHOD_ALL,
                $this->path("password-reset/{token}"), [
                    'group'      => $this->name(),
                    'controller' => 'auth',
                    'action'     => 'password-reset',
                ])
            ->backendRoute(
                $this->name('profile'),
                Router::METHOD_ALL,
                $this->path("profile"), [
                    'group'      => $this->name(),
                    'controller' => 'profile',
                    'action'     => 'update',
                ])
            ->backendRoute(
                $this->name('select'),
                Router::METHOD_ALL,
                $this->path("select/{type}?"), [
                    'group'      => $this->name(),
                    'controller' => 'index',
                    'action'     => 'select',
                    'type'       => 'one',
                ])
            ->backendRoute(
                $this->name('site'),
                Router::METHOD_ALL,
                $this->path("site"), [
                    'group'      => $this->name(),
                    'controller' => 'site',
                    'action'     => 'index',
                ])
            ->backendRoute(
                $this->name('setting'),
                Router::METHOD_ALL,
                $this->path("setting"), [
                    'group'      => $this->name(),
                    'controller' => 'setting',
                    'action'     => 'index',
                ])
            ->backendRoute(
                $this->name('widget'),
                Router::METHOD_ALL,
                $this->path("widget/{action}/{entity}"), [
                    'group'      => $this->name(),
                    'controller' => 'widget',
                ])
            ->backendRoute(
                $this->name('frontend'),
                Router::METHOD_ALL,
                $this->path("frontend/{entityType}/{entityId}"), [
                    'group'      => $this->name(),
                    'controller' => 'index',
                    'action'     => 'frontend',
                ])
            ->backendRoute(
                $this->name('about'),
                Router::METHOD_ALL,
                $this->path("about"), [
                    'group'      => $this->name(),
                    'controller' => 'index',
                    'action'     => 'about',
                ]);

        $this
            ->backendHookListen($this->name('contentList'), function(InfoList $list) {
                if ($list->filter() == $this->name('node')) {
                    $list
                        ->item($this->name('page'), [])
                        ->item($this->name('file'), [])
                        ->item($this->name('url'), [])
                        ->item($this->name('alias'), []);
                } else if ($list->filter() == $this->name('link')) {
                    $list
                        ->item($this->name('page'), [])
                        ->item($this->name('file'), [])
                        ->item($this->name('url'), [])
                        ->item($this->name('alias'), []);
                } else if ($list->filter() == $this->name('image')) {
                    $list
                        ->item($this->name('file'), [
                            'subtypes' => [
                                'image/gif',
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'image/svg+xml',
                            ],
                        ]);
                }
                return $list;
            })
            ->backendHookListen($this->name('widgetList'), function(InfoList $list) {
                return $list;
            })
            ->backendHookListen($this->name('nav'), function(Nav $nav) {
                $nav
                    ->item($this->name('site'), [
                        'label'     => 'Site',
                        'icon'      => 'publish',
                        'url'       => ['name' => $this->name('site')],
                    ])
                    ->item($this->name('setting'), [
                        'label'     => 'Settings',
                        'icon'      => 'settings',
                        'url'       => ['name' => $this->name('setting')],
                    ]);
                $nav
                    ->entity($this->name('page'), [], $this->name('site'))
                    ->entity($this->name('block'), [], $this->name('page'))
                    ->entity($this->name('file'), [], $this->name('site'))
                    ->entity($this->name('url'), [], $this->name('site'))
                    ->entity($this->name('alias'), [], $this->name('site'));
                $nav
                    ->entity($this->name('domain'), [], $this->name('setting'))
                    ->entity($this->name('structure'), [], $this->name('domain'))
                    ->entity($this->name('user'), [], $this->name('setting'))
                    ->entity($this->name('tag'), [], $this->name('setting'));
                return $nav;
            })
            ->backendHookListen($this->name('select'), function(Nav $nav) {
                $nav
                    ->entity($this->name('page'), [])
                    ->entity($this->name('block'), [], $this->name('page'))
                    ->entity($this->name('file'), [])
                    ->entity($this->name('url'), [])
                    ->entity($this->name('alias'), [])
                    ->entity($this->name('user'), [])
                    ->entity($this->name('tag'), []);
                return $nav;
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
                        "{$primary}",
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
       $entities = $this->em('core_content')->all(['isPublishable' => true]);
       foreach ($entities as $entity) {
           $entity->status = Chalk::STATUS_PUBLISHED;
       }
        $this->em->flush();
    }

    public function contentList($filters)
    {
        if (is_array($filters)) {
            $infoList = new \Chalk\InfoList();
            foreach ($filters as $name => $subtypes) {
                $subtypes = is_array($subtypes)
                    ? $subtypes
                    : [];
                $infoList->item($name, [
                    'subtypes' => $subtypes,
                ]);
            }
            return $infoList;
        } else {
            return $this->backend->hook->fire($this->name('contentList'), new \Chalk\InfoList($filters));
        }
    }
}