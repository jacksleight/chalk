<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk;

use Closure;
use Chalk\Hook;

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
        if ($value instanceof Hook) {
            $value->preFire();
        }
        if (isset($this->hooks[$name])) {
            foreach ($this->hooks[$name] as $listener) {
                $value = $listener($value);
            }
        }
        if ($value instanceof Hook) {
            $value->postFire();
        }
        return $value;
    }
}