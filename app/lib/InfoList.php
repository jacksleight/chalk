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
    protected $_filter;

    protected $_items = [];

    public function __construct($filter = null)
    {
        $this->_filter = $filter;
    }

    public function filter($filter = null)
    {
        if (func_num_args() > 0) {
            $this->_filter = $filter;
            return $this;
        }
        return $this->_filter;  
    }

    public function item($name, $info = null)
    {
        if (func_num_args() > 1) {
            if (isset($info)) {
                $this->_items[$name] = (object) (((array) Chalk::info($name)) + $info + ['subtypes' => []]);
            } else {
                unset($this->_items[$name]);
            }
            return $this;
        }
        return $this->_items[$name];  
    }

    public function items(array $items = null)
    {
        if (func_num_args() > 0) {
            foreach ($items as $name => $info) {
                $this->item($name, $info);
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
        reset($this->_items);
        return count($this->_items) ? current($this->_items) : null;
    }

    public function last()
    {
        end($this->_items);
        return count($this->_items) ? current($this->_items) : null;
    }
}