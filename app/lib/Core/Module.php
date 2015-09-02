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
use Chalk\Module as ChalkModule;
use Closure;
use Coast\Request;
use Coast\Response;
use Coast\Router;

class Module extends ChalkModule
{
    protected $_contentEntities = [];

    public function __construct()
    {
        parent::__construct('core', '../../..');
    }
    
    public function init()
    {
        $this
            ->entityDir($this->name(), 'app/lib');

        $this
            ->contentEntity($this->name('page'))
            ->contentEntity($this->name('file'))
            ->contentEntity($this->name('block'))
            ->contentEntity($this->name('url'))
            ->contentEntity($this->name('blank'));
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

        $this->map = [];
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
            $this->map[$node['id']] = $node;
        }
    }
    
    public function backendInit()
    {
        $this
            ->backendControllerAll($this->name())
            ->backendControllerNspace($this->name())
            ->backendViewDir($this->name());

        $this
            ->backendRoute('index', Router::METHOD_ALL, "{controller}?/{action}?/{id}?", [
                'controller' => 'index',
                'action'     => 'index',
                'id'         => null,
            ])
            ->backendRoute('about', Router::METHOD_ALL, "about", [
                'controller' => 'auth',
                'action'     => 'about',
            ])
            ->backendRoute('sandbox', Router::METHOD_ALL, "sandbox", [
                'controller' => 'index',
                'action'     => 'sandbox',
            ])
            ->backendRoute('passwordRequest', Router::METHOD_ALL, "password-request", [
                'controller' => 'auth',
                'action'     => 'password-request',
            ])
            ->backendRoute('passwordReset', Router::METHOD_ALL, "password-reset/{token}", [
                'controller' => 'auth',
                'action'     => 'password-reset',
            ])
            ->backendRoute('login', Router::METHOD_ALL, "login", [
                'controller' => 'auth',
                'action'     => 'login',
            ])
            ->backendRoute('logout', Router::METHOD_ALL, "logout", [
                'controller' => 'auth',
                'action'     => 'logout',
            ])
            ->backendRoute('profile', Router::METHOD_ALL, "profile", [
                'controller' => 'profile',
                'action'     => 'edit',
            ])
            ->backendRoute('prefs', Router::METHOD_ALL, "prefs", [
                'controller' => 'index',
                'action'     => 'prefs',
            ])
            ->backendRoute('content', Router::METHOD_ALL, "content/{entity}?/{action}?/{content}?", [
                'controller' => 'content',
                'action'     => 'index',
                'entity'     => null,
                'content'    => null,
            ])
            ->backendRoute('setting', Router::METHOD_ALL, "setting/{controller}?/{action}?/{id}?", [
                'controller' => 'setting',
                'action'     => 'index',
                'id'         => null,
            ])
            ->backendRoute('widget', Router::METHOD_ALL, "widget/{action}/{entity}", [
                'controller' => 'widget',
            ])
            ->backendRoute('structure', Router::METHOD_ALL, "structure/{action}?/{structure}?", [
                'controller' => 'structure',
                'action'     => 'index',
                'structure'  => null,
            ])
            ->backendRoute('structure_node', Router::METHOD_ALL, "structure/node/{structure}/{action}?/{node}?", [
                'controller' => 'structure_node',
                'action'     => 'index',
                'node'       => null,
            ]);

        $this
            ->backendNavItem($this->name('primary'), [])
            ->backendNavItem($this->name('secondary'), [])
            ->backendNavItem($this->name('structure'), [
                'label' => 'Structure',
                'icon'  => 'structure',
                'url'   => [[], 'structure'],
            ], $this->name('primary'))
            ->backendNavItem($this->name('content'), [
                'label' => 'Content',
                'icon'  => 'content',
                'url'   => [[], 'content'],
            ], $this->name('primary'))
            ->backendNavItem($this->name('setting'), [
                'label' => 'Settings',
                'icon'  => 'settings',
                'url'   => [[], 'setting'],
            ], $this->name('secondary'))
            ->backendNavItem($this->name('setting\domain'), [
                'label' => 'Site',
                'url'   => [['controller' => 'setting_domain', 'action' => 'edit', 'id' => 1], 'setting'],
            ], $this->name('setting'))
            ->backendNavItem($this->name('setting\user'), [
                'label' => 'Users',
                'url'   => [['controller' => 'setting_user'], 'setting'],
            ], $this->name('setting'))
            ->backendNavItem($this->name('setting\structure'), [
                'label' => 'Structures',
                'url'   => [['controller' => 'setting_structure'], 'setting'],
            ], $this->name('setting'));

        $this->backend->event
            ->register($this->name('listWidgets'), $this->nspace('Event\ListWidgets'));
    }

    public function contentEntity($name, $remove = false)
    {
        $info = Chalk::info($name);
        if ($remove) {
            unset($this->_contentEntities[$info->name]);
        } else {
            $this->_contentEntities[$info->name] = $info;
        }
        return $this;  
    }

    public function contentEntities(array $contentEntitys = null)
    {
        if (func_num_args() > 1) {
            foreach ($contentEntitys as $name) {
                $this->contentEntity($name);
            }
            return $this;
        }
        return $this->_contentEntities;
    }
}