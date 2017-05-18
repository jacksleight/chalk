<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Chalk;
use Chalk\Core\User;
use Coast\Resolver;

class NavList
{
    protected $_user;
    protected $_items = [
        0 => ['children' => []],
    ];

    public function __construct(User $user)
    {
        $this->_user = $user;
    }

    public function item($name, $item = null, $parent = 0)
    {
        if (func_num_args() > 1) {
            if (isset($item)) {
                if (isset($this->_items[$name])) {
                    $this->_items[$name] = \Coast\array_merge_smart($this->_items[$name], $item);
                } else {
                    $this->_items[$name] = \Coast\array_merge_smart([
                        'label'        => null,
                        'badge'        => null,
                        'icon'         => null,
                        'url'          => [
                            'params' => [],
                            'name'   => null,
                        ],
                        'parent'       => $parent,
                        'children'     => [],
                        'sort'         => (count($this->_items[$parent]['children']) + 1) * 10,
                        'isTags'       => false,
                        'isActive'     => false,
                        'isActivePath' => false,
                    ], $item);
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

    public function itemEntity($name, $item = null, $parent = 0)
    {
        $info = Chalk::info($name);
        $name = $info->name;
        if (func_num_args() > 1) {
            if (isset($item)) {
                $this->item($name, \Coast\array_merge_smart([
                    'info'  => $info,
                    'label' => $info->plural,
                    'icon'  => $info->icon,
                    'url'   => ['params' => [
                        'group'      => $info->module->name,
                        'controller' => $info->local->name,
                    ]],
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
        if (!isset($this->_items[$name]['children'])) {
            return null;
        }
        $items = $this->_items[$name]['children'];
        foreach ($items as $name => $item) {
            if (isset($item['roles']) && !in_array($this->_user->role, $item['roles'])) {
                unset($items[$name]);
            }
        }
        uasort($items, function($a, $b) {
            return $a['sort'] - $b['sort'];
        });
        return $items;
    }

    public function activate(Resolver $url, $active)
    {
        $map = [];
        foreach ($this->_items as $name => $item) {
            if (!isset($item['url'])) {
                continue;
            }
            $path = $url->route($item['url']['params'], $item['url']['name'], true, false);
            $this->_items[$name]['url'] = $url->string($path);
            $map[$path->toString()] = $name;
        }
        foreach (array_reverse($map) as $path => $name) {
            if (strpos($active, $path) === 0) {
                $found = $name;
                break;
            }
        }
        if (isset($found)) {
            $i = 0;
            do {
                if ($i == 0) {
                    $this->_items[$found]['isActive'] = true;
                }
                $this->_items[$found]['isActivePath'] = true;
                $found = $this->_items[$found]['parent'];
                $i++;
            } while ($found !== 0);
        }
        return $this;
    }
}