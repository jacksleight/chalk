<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Closure;

class EventManager
{
    public function register($name, $class = 'Chalk\Event')
    {
        if (!is_a($class, 'Chalk\Event', true)) {
            throw new \Exception("Class '{$class}' is not a subclass of Chalk\Event");
        }
        $this->_events[$name] = [
            'class'     => $class,
            'listeners' => [],
        ];
        return $this;
    }

    public function listen($name, Closure $listener)
    {
        if (!isset($this->_events[$name])) {
            return $this;
        }
        $this->_events[$name]['listeners'][] = $listener;
        return $this;
    }

    public function fire($name)
    {
        $args = func_get_args();
        array_shift($args);

        if (!isset($this->_events[$name])) {
            return $this;
        }

        $class = $this->_events[$name]['class'];
        $event = new $class();
        array_unshift($args, $event);
        foreach ($this->_events[$name]['listeners'] as $listener) {
            call_user_func_array($listener, $args);
        }
        return $event;
    }
}