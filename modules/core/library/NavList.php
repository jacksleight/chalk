<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\App as Chalk;
use Coast\Resolver;

class NavList
{
    protected $_i = 0;

    protected $_items = [
        0 => ['children' => []],
    ];

    public function item($name, $item = null, $parent = 0)
    {
        if (func_num_args() > 1) {
            if (isset($item)) {
                if (isset($this->_items[$name])) {
                    $this->_items[$name] = $item + $this->_items[$name];
                } else {
                    $this->_items[$name] = $item + [
                        'i'            => $this->_i++,
                        'label'        => null,
                        'badge'        => null,
                        'icon'         => null,
                        'url'          => null,
                        'parent'       => $parent,
                        'children'     => [],
                        'sort'         => 0,
                        'isActive'     => false,
                        'isActivePath' => false,
                    ];
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

    public function itemEntity($name, $item = null, $parent = 0, $route = 'core_site')
    {
        $info = Chalk::info($name);
        $name = $info->name;
        if (func_num_args() > 1) {
            if (isset($item)) {
                $this->item($name, [
                    'label' => $info->plural,
                    'icon'  => $info->icon,
                    'url'   => [[
                        'group'      => $info->module->name,
                        'controller' => $info->local->name,
                    ], 'core_site'],
                ] + $item, $parent);
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
        uasort($items, function($a, $b) {
            return $a['sort'] == $b['sort']
                ? $a['i'] - $b['i']
                : $a['sort'] - $b['sort'];
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
            $path = $url->route($item['url'][0], $item['url'][1], true, false);
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