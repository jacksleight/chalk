<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md.
 */

namespace Chalk;

use Chalk\Chalk;

abstract class Model extends \Toast\Entity
{
    public function __set($name, $value)
    {
        try {
            return parent::__set($name, $value);
        } catch (\Exception $e) {}
    }

    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (\Exception $e) {}
    }

    public function __isset($name)
    {
        try {
            return parent::__isset($name);
        } catch (\Exception $e) {}
    }

    public function __unset($name)
    {
        try {
            return parent::__unset($name);
        } catch (\Exception $e) {}
    }
}