<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk;

use Closure;

class HookManager
{
    protected $_hooks = [];

    public function listen($name, Closure $listener)
    {
        $this->hooks[$name][] = $listener;
        return $this;
    }

    public function fire($name, $value = null)
    {
        if (!isset($this->hooks[$name])) {
            return $value;
        }
        foreach ($this->hooks[$name] as $listener) {
            $value = $listener($value);
        }
        return $value;
    }
}