<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk;

use Chalk\Chalk;
use Iterator;
use Countable;
use Chalk\Hook;

class Info implements Iterator, Countable, Hook
{
    protected $_items = [];

    public function item($name, $info = null)
    {
        if (func_num_args() > 1) {
            if (isset($info)) {
                $this->_items[$name] = (object) ($info + ((array) Chalk::info($name)) + [
                    'subs' => [],
                ]);
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

    public function has($name)
    {
        return isset($this->_items[$name]);
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

    public function fetch($flag, $match = true)
    {
        $items = [];
        foreach ($this->_items as $item) {
            if ($match) {
                if (isset($item->{$flag}) && $item->{$flag}) {
                    $items[] = $item;
                }
            } else {
                if (!isset($item->{$flag}) || !$item->{$flag}) {
                    $items[] = $item;
                }
            }
        }
        return $items;
    }

    public function preFire()
    {}

    public function postFire()
    {
        uasort($this->_items, function($a, $b) {
            return strcmp("{$a->group} {$a->singular}", "{$b->group} {$b->singular}");
        });
    }
}