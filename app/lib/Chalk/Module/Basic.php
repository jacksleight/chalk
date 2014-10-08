<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Module;

use Chalk\Module,
    Coast\Dir,
	Coast\File;

abstract class Basic implements Module
{
    protected $_baseDir;

    public function __construct()
    {
		$reflection	= new \ReflectionClass(get_class($this));
		$baseDir = (new File($reflection->getFileName()))
			->dir()
            ->dir('..')
			->dir('..')
			->toReal();
        $this->baseDir(!$baseDir instanceof Dir
            ? new Dir("{$baseDir}")
            : $baseDir);
    }

    public function baseDir(\Coast\Dir $baseDir = null)
    {
        if (isset($baseDir)) {
            $this->_baseDir = $baseDir;
            return $this;
        }
        return $this->_baseDir;
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

    public function libDir()
    {
        return $this->dir('lib');
    }

    public function viewDir()
    {
        return $this->dir('views/admin');
    }

    public function controllerNamespace()
    {
        return get_class($this) . '\\Controller';
    }
}