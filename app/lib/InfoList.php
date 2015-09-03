<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Iterator;
use Countable;

class InfoList implements Iterator, Countable
{
    protected $_items = [];

    public function item($name, $remove = false)
    {
        $info = Chalk::info($name);
        if ($remove) {
            unset($this->_items[array_search($info, $this->_items)]);
        } else {
            $this->_items[] = $info;
        }
        return $this;  
    }

    public function items(array $items = null)
    {
        if (func_num_args() > 1) {
            foreach ($items as $name) {
                $this->item($name);
            }
            return $this;
        }
        return $this->_items;
    }

    public function rewind()
    {
        return reset($this->_items);
    }

    public function current()
    {
        return current($this->_items);
    }

    public function key()
    {
        return key($this->_items);
    }

    public function next()
    {
        return next($this->_items);
    }

    public function valid()
    {
        return key($this->_items) !== null;
    }

    public function count()
    {
        return count($this->_items);
    }

    public function first()
    {
        return count($this->_items) ? $this->_items[0] : null;
    }

    public function last()
    {
        return count($this->_items) ? $this->_items[count($this->_items) - 1] : null;
    }
}