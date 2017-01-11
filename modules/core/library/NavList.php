<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

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
                        'i'         => $this->_i++,
                        'label'     => null,
                        'badge'     => null,
                        'icon'      => null,
                        'url'       => null,
                        'children'  => [],
                        'sort'      => 0,
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
}