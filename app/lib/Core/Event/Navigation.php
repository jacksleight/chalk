<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Event;

use Chalk\Event;

class Navigation extends Event
{
    protected $_items = [
        0 => ['children' => []],
    ];

    public function item($name, $item = null, $parent = 0)
    {
        if (func_num_args() > 1) {
            if (isset($item)) {
                $this->_items[$name] = $item + [
                    'label'    => null,
                    'badge'    => null,
                    'icon'     => null,
                    'url'      => null,
                    'children' => [],
                ];
                $this->_items[$parent]['children'][$name] = &$this->_items[$name];
            } else {
                $this->_items[$name] = null;
            }
            return $this;
        }
        return $this->_items[$name];
    }

    public function items($parent = 0)
    {
        return isset($this->_items[$parent]['children'])
            ? $this->_items[$parent]['children']
            : null;
    }
}