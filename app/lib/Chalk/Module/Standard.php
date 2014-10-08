<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Module;

use Chalk,
    Chalk\Module,
	Coast\File,
	Coast\Dir,
	ReflectionClass;

abstract class Standard implements Module
{
    protected $_baseDir;

    public function __construct($baseDir = '../../..')
    {
    	if (!$baseDir instanceof Dir) {
    		$reflection	= new ReflectionClass(get_class($this));
			$baseDir = (new File($reflection->getFileName()))
				->dir()
	            ->dir("{$baseDir}")
				->toReal();
    	}
        $this->baseDir($baseDir);
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
}