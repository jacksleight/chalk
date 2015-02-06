<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Chalk\Chalk,
    Chalk\Module,
	Coast\File,
	Coast\Dir,
	ReflectionClass,
    Closure;

abstract class Module
{
    protected $_baseDir;
    protected $_chalk;

    public function __construct($baseDir = '../')
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

    public function baseDir(Dir $baseDir = null)
    {
        if (isset($baseDir)) {
            $this->_baseDir = $baseDir;
            return $this;
        }
        return $this->_baseDir;
    }

    public function chalk(Chalk $chalk = null)
    {
        if (isset($chalk)) {
            $this->_chalk = $chalk;
            return $this;
        }
        return $this->_chalk;
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

    public function emDir($path)
    {
        $this->_chalk->em->dir(
            $this->nspace(),
            $this->dir($path)
        );
        return $this;
    }

    public function viewDir($path)
    {
        $this->_chalk->view->dir(
            $this->nspace(),
            $this->dir($path)
        );
        return $this;
    }

    public function controllerNspace($class)
    {
        $this->_chalk->controller->nspace(
            $this->nspace(),
            $this->nspace($class)
        );
        return $this;
    }

    public function controllerAll($class)
    {
        $this->_chalk->controller->all(
            [$class, $this->nspace()]
        );
        return $this;
    }

    public function frontendViewDir()
    {
        $this->_chalk->frontend->view->dir(
            $this->nspace(),
            $this->_chalk->config->viewDir->dir(Chalk::info($this->nspace())->name)
        );
        return $this;
    }

    public function register($class)
    {
        $this->_chalk->register(
            $this->nspace($class)
        );
        return $this;
    }

    public function listen($class, callable $listener)
    {
        $this->_chalk->listen(
            $class,
            $listener instanceof Closure ? $listener->bindTo($this) : $listener
        );
        return $this;
    }
}