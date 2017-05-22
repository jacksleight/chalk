<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Chalk;
use Chalk\Hook;
use Chalk\Core\User;
use Coast\Resolver;
use Coast\Request;

class Nav implements Hook
{
    protected $_url;
    protected $_req;

    protected $_main;
    protected $_items = [
        'root' => [
            'name'     => 'root',
            'children' => [],
            'parent'   => null,
        ],
    ];

    public function __construct(Resolver $url, Request $req)
    {
        $this->_url = $url;
        $this->_req = $req;
    }

    public function main()
    {
        return $this->_main;
    }

    public function item($name, $item = null, $parent = 'root')
    {
        if (func_num_args() > 1) {
            if (isset($item)) {
                if (isset($this->_items[$name])) {
                    $this->_items[$name] = \Coast\array_merge_smart($this->_items[$name], $item);
                } else {
                    $this->_items[$name] = \Coast\array_merge_smart([
                        'name'         => $name,
                        'label'        => null,
                        'badge'        => null,
                        'icon'         => null,
                        'url'          => [
                            'params' => [],
                            'name'   => null,
                        ],
                        'parent'       => null,
                        'children'     => [],
                        'sort'         => (count($this->_items[$parent]['children']) + 1) * 10,
                        'isTagable'    => false,
                        'isActive'     => false,
                        'isActivePath' => false,
                    ], $item);
                    if ($parent != 'root') {
                        $this->_items[$name]['parent'] = &$this->_items[$parent];
                    }
                    $this->_items[$parent]['children'][$name] = &$this->_items[$name];
                }
            } else {
                unset($this->_items[$name]);
                foreach ($this->_items as $parent => $item) {
                    unset($this->_items[$parent]['children'][$name]);
                }
            }
            return $this;   
        }
        return isset($this->_items[$name])
            ? $this->_items[$name]
            : null;
    }

    public function items(array $items = null)
    {
        if (func_num_args() > 1) {
            foreach ($items as $name => $value) {
                $this->item($name, $value[0], $value[1]);
            }
            return $this;
        }
        return $this->_items;
    }

    public function entity($name, $item = null, $parent = 'root')
    {
        $info = Chalk::info($name);
        if (func_num_args() > 1) {
            if (isset($item)) {
                $this->item($name, \Coast\array_merge_smart([
                    'info'  => $info,
                    'label' => $info->plural,
                    'icon'  => $info->icon,
                    'url'   => ['params' => [
                        'controller' => $info->local->name,
                    ], 'name' => "{$info->module->name}_index"],
                    'isTagable' => $info->is->tagable,
                ], $item), $parent);
            } else {
                $this->item($name, $item);
            }
            return $this;   
        }
        return $this->item($name);
    }

    public function children($name)
    {
        $items = $this->_items[$name]['children'];
        uasort($items, function($a, $b) {
            return $a['sort'] - $b['sort'];
        });
        return $items;
    }

    public function preFire()
    {}

    public function postFire()
    {
        $map = [];
        foreach ($this->_items as $name => $item) {
            if (!isset($item['url'])) {
                continue;
            }
            $url  = $this->_url->route($item['url']['params'], $item['url']['name'], true);
            $path = $this->_url->route($item['url']['params'], $item['url']['name'], true, false);
            $this->_items[$name]['url'] = $url;
            $map[$path->toString()] = $name;
        }
        $active = $this->_req->path();
        foreach (array_reverse($map) as $path => $name) {
            if (strpos($active, $path) === 0) {
                $item = $this->_items[$name];
                break;
            }
        }
        $i = 0;
        do {
            $this->_items[$item['name']]['isActive'] = $i == 0;
            $this->_items[$item['name']]['isActivePath'] = true;
            $main = $item;
            $item = $item['parent'];
            $i++;
        } while (isset($item));
        $this->_main = $main;
        return $this;
    }
}