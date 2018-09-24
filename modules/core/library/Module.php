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
use Chalk\Info;
use Chalk\Nav;
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
    const NAME    = 'core';
    const VERSION = Chalk::VERSION;

    public function init()
    {
        $this
            ->entityDir('core')
            ->entityListener('core_file', new FileListener())
            ->entityListener('core_structure_node', new StructureNodeListener())
            ->entityListener('core_publishable', new PublishableListener())
            ->entityListener('core_searchable', new SearchableListener())
            ->entityListener('core_trackable', $trackable = new TrackableListener());
    }

    public function frontendInit()
    {
        $this
            ->frontendControllerAll('core')
            ->frontendControllerNspace('core')
            ->frontendViewDir('core');

        $this
            ->frontendParserPlugin('core_frontend', new PluginFrontend())
            ->frontendParserPlugin('core_stripEmpty', new StripEmpty([
                'tags' => ['p'],
            ]));

        $this
            ->frontendResolver('core_domain', function($entity, $info) {
                return $this->frontend->url();
            })
            ->frontendResolver('core_structure_node', function($entity, $info) {
                try {
                    return $this->frontend->url->route([], "core_structure_node_{$entity['id']}", true);   
                } catch (Router\Exception $e) {}
            })
            ->frontendResolver('core_content', function($entity, $info) {
                try {
                    return $this->frontend->url->route([], "core_content_{$entity['id']}", true);
                } catch (Router\Exception $e) {}
            })
            ->frontendResolver('core_url', function($entity, $info) {
                return $entity['url'];
            })
            ->frontendResolver('core_file', function($entity, $info) {
                if (is_array($entity)) {
                    // @todo Remove this once File works with array hydration
                    $entity = $this->em('core_file')->id($entity['id']);
                }
                return $this->frontend->url
                    ->file($entity['file'], true, true, false);
            });

        if ($this->app->isHttp()) {
            $this->frontendInitDomain();
            $this->frontendInitNodes();
        }

        $this
            ->frontendRoute(
                'core_robots',
                Router::METHOD_ALL,
                "robots.txt", [
                    'group'      => 'core',
                    'controller' => 'index',
                    'action'     => 'robots',
                ]);

        if ($this->app->module('sitemap')) {
            $this
                ->frontendHookListen('sitemap_xml', function(Sitemap $sitemap) {
                    foreach ($this->frontend->domain['structures'] as $structure) {
                        $nodes = $this->frontend->children($structure['nodes'][0], true);
                        foreach ($nodes as $node) {
                            $content = $node['content'];
                            if (isset($content['data']['delegate'])) {
                                continue;
                            }
                            $sitemap->urls[] = (new Sitemap\Url())->fromArray([
                                'url'        => $this->frontend->url($node),
                                'updateDate' => $content['updateDate'],
                            ]);
                        }
                    }
                    return $sitemap;
                });
        }
    }

    public function frontendInitDomain()
    {
        $domain = $this->em('core_domain')->id(1, [], [
            'hydrate' => Repository::HYDRATE_ARRAY,
        ]);
        $structures = $this->em('core_structure')->all([], [
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
                c.data AS content_data,
                'Chalk\\\Core\\\Structure\\\Node' as __CLASS__
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
                    "core_content_{$content['id']}",
                    $primary
                )
                ->frontendRouteAlias(
                    "core_structure_node_{$node['id']}",
                    $primary
                );
        }
        $this->frontend->nodeMap = $nodeMap;
    }

    public function backendInit()
    {
        $this
            ->backendControllerAll('core')
            ->backendControllerNspace('core')
            ->backendViewDir('core');

        $this
            ->backendParserPlugin('core_backend', new PluginBackend());

        $this
            ->backendResolver(null, function($entity, $info) {
                try {
                    if ($entity['id'] == 0) {
                        $info = Chalk::info($entity['__CLASS__']);
                        return [
                            'name'   => "{$info->module->name}_index",
                            'params' => [
                                'controller' => $info->local->name,
                            ],
                        ];
                    } else {
                        $info = Chalk::info($entity);
                        return [
                            'name'   => "{$info->module->name}_index",
                            'params' => [
                                'controller' => $info->local->name,
                                'action'     => 'update',
                                'id'         => $entity->id,
                            ],
                        ];
                    }
                } catch (Router\Exception $e) {}
            });

        $this
            ->backendRoute(
                'core_null',
                Router::METHOD_ALL,
                "", [
                    'group'      => 'core',
                    'controller' => 'index',
                    'action'     => 'index',
                ])
            ->backendRoute(
                'core_index',
                Router::METHOD_ALL,
                $this->path("{controller}?/{action}?/{id}?"), [
                    'group'      => 'core',
                    'controller' => 'index',
                    'action'     => 'index',
                    'id'         => null,
                ])
            ->backendRoute(
                'core_login',
                Router::METHOD_ALL,
                $this->path("login"), [
                    'group'      => 'core',
                    'controller' => 'auth',
                    'action'     => 'login',
                ])
            ->backendRoute(
                'core_logout',
                Router::METHOD_ALL,
                $this->path("logout"), [
                    'group'      => 'core',
                    'controller' => 'auth',
                    'action'     => 'logout',
                ])
            ->backendRoute(
                'core_passwordRequest',
                Router::METHOD_ALL,
                $this->path("password-request"), [
                    'group'      => 'core',
                    'controller' => 'auth',
                    'action'     => 'password-request',
                ])
            ->backendRoute(
                'core_passwordReset',
                Router::METHOD_ALL,
                $this->path("password-reset/{token}"), [
                    'group'      => 'core',
                    'controller' => 'auth',
                    'action'     => 'password-reset',
                ])
            ->backendRoute(
                'core_profile',
                Router::METHOD_ALL,
                $this->path("profile"), [
                    'group'      => 'core',
                    'controller' => 'profile',
                    'action'     => 'update',
                ])
            ->backendRoute(
                'core_select',
                Router::METHOD_ALL,
                $this->path("select/{mode}?"), [
                    'group'      => 'core',
                    'controller' => 'index',
                    'action'     => 'select',
                ])
            ->backendRoute(
                'core_site',
                Router::METHOD_ALL,
                $this->path("site"), [
                    'group'      => 'core',
                    'controller' => 'site',
                    'action'     => 'index',
                ])
            ->backendRoute(
                'core_setting',
                Router::METHOD_ALL,
                $this->path("setting"), [
                    'group'      => 'core',
                    'controller' => 'setting',
                    'action'     => 'index',
                ])
            ->backendRoute(
                'core_widget',
                Router::METHOD_ALL,
                $this->path("widget"), [
                    'group'      => 'core',
                    'controller' => 'widget',
                    'action'     => 'update',
                ])
            ->backendRoute(
                'core_frontend',
                Router::METHOD_ALL,
                $this->path("frontend/{ref:.*}"), [
                    'group'      => 'core',
                    'controller' => 'index',
                    'action'     => 'frontend',
                ])
            ->backendRoute(
                'core_backend',
                Router::METHOD_ALL,
                $this->path("backend/{ref:.*}"), [
                    'group'      => 'core',
                    'controller' => 'index',
                    'action'     => 'backend',
                ])
            ->backendRoute(
                'core_jump',
                Router::METHOD_ALL,
                $this->path("jump/{ref:.*}"), [
                    'group'      => 'core',
                    'controller' => 'auth',
                    'action'     => 'jump',
                ])
            ->backendRoute(
                'core_about',
                Router::METHOD_ALL,
                $this->path("about"), [
                    'group'      => 'core',
                    'controller' => 'index',
                    'action'     => 'about',
                ]);

        $this
            ->backendHookListen('core_info_link', function(Info $info) {
                $info
                    ->item('core_structure_node', [])
                    ->item('core_file', [])
                    ->item('core_url', [])
                    ->item('core_alias', []);
                return $info;
            })
            ->backendHookListen('core_info_node', function(Info $info) {
                $info
                    ->item('core_page', [
                        'isPrimary' => true,
                    ])
                    ->item('core_file', [
                        'isExisting' => true,
                    ])
                    ->item('core_url', [])
                    ->item('core_alias', []);
                return $info;
            })
            ->backendHookListen('core_info_image', function(Info $info) {
                $info
                    ->item('core_file', [
                        'subs' => [
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/svg+xml',
                            'image/webp',
                        ],
                    ]);
                return $info;
            })
            ->backendHookListen('core_info_video', function(Info $info) {
                $info
                    ->item('core_file', [
                        'subs' => [
                            'video/mp4',
                            'video/ogg',
                            'video/webm',
                            'video/x-m4v',
                        ],
                    ]);
                return $info;
            })
            ->backendHookListen('core_info_audio', function(Info $info) {
                $info
                    ->item('core_file', [
                        'subs' => [
                            'audio/mpeg',
                            'audio/ogg',
                            'audio/webm',
                            'audio/x-wav',
                        ],
                    ]);
                return $info;
            })
            ->backendHookListen('core_nav', function(Nav $nav) {
                $nav
                    ->item('core_site', [
                        'label'     => 'Site',
                        'icon'      => 'publish',
                        'url'       => ['name' => 'core_site'],
                        'sort'      => 10,
                    ])
                    ->item('core_setting', [
                        'label'     => 'Settings',
                        'icon'      => 'settings',
                        'url'       => ['name' => 'core_setting'],
                        'sort'      => 90,
                    ]);
                $nav
                    ->entity('core_structure_node', [], 'core_site')
                    ->entity('core_block', [], 'core_structure_node')
                    ->entity('core_file', [], 'core_site')
                    ->entity('core_url', [], 'core_site')
                    ->entity('core_alias', [], 'core_site');
                $nav
                    ->entity('core_domain', [], 'core_setting')
                    ->entity('core_structure', [
                        'roles' => ['developer'],
                    ], 'core_domain')
                    ->entity('core_user', [
                        'roles' => ['administrator', 'developer'],
                    ], 'core_setting')
                    ->entity('core_tag', [], 'core_setting');
                return $nav;
            })
            ->backendHookListen('core_select', function(Nav $nav) {
                $nav
                    ->entity('core_structure_node', [])
                    ->entity('core_block', [], 'core_structure_node')
                    ->entity('core_page', [])
                    ->entity('core_file', [])
                    ->entity('core_url', [])
                    ->entity('core_alias', [])
                    ->entity('core_user', [])
                    ->entity('core_tag', []);
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
                $primary = "core_{$name}_{$content['id']}";
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
}