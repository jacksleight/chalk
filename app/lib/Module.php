<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Chalk\Backend;
use Chalk\Chalk;
use Chalk\Frontend;
use Chalk\Module;
use Closure;
use Coast\App\Access;
use Coast\Dir;
use Coast\File;
use ReflectionClass;

abstract class Module implements Access
{
    use Access\Implementation;

    protected $_name;

    protected $_baseDir;

    public function __construct($name, $baseDir = '..')
    {
        $this->_name = $name;
        if (!$baseDir instanceof Dir) {
            $reflection = new ReflectionClass(get_class($this));
            $baseDir = (new File($reflection->getFileName()))
                ->dir()
                ->dir("{$baseDir}")
                ->toReal();
    	}
        $this->baseDir($baseDir);
    }

    public function baseDir(Dir $baseDir = null)
    {
        if (isset($baseDir)) {
            $this->_baseDir = $baseDir;
            return $this;
        }
        return $this->_baseDir;
    }

    public function name($name = null)
    {
        return isset($name)
            ? $this->_name . '_' . $name
            : $this->_name;
    }

    public function nspace($nspace = null)
    {
        $class = get_class($this);
        $class = substr($class, 0, strrpos($class, '\\'));
        return isset($nspace)
            ? $class . '\\' . $nspace
            : $class;
    }

    public function dir($path = null, $create = false)
    {
        return isset($path)
            ? $this->_baseDir->dir($path, $create)
            : $this->_baseDir;
    }

    public function file($path)
    {
        return $this->_baseDir->file($path);
    }

    public function init()
    {}

    public function initBackend()
    {}

    public function initFrontend()
    {}
}