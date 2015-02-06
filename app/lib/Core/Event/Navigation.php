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

    public function item($name, $item, $parent = 0)
    {
        $this->_items[$name] = $item + [
            'label'    => null,
            'badge'    => null,
            'icon'     => null,
            'url'      => null,
            'children' => [],
        ];
        $this->_items[$parent]['children'][$name] = &$this->_items[$name];
        return $this;
    }

    public function items($parent = 0)
    {
        return isset($this->_items[$parent]['children'])
            ? $this->_items[$parent]['children']
            : null;
    }
}