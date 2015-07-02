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
    protected $_name;

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

    public function chalk(Chalk $chalk = null, $name = null)
    {
        if (isset($chalk)) {
            $this->_chalk = $chalk;
            $this->_name  = $name;
            return $this;
        }
        return $this->_chalk;
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
        $this->_chalk->backend->view->dir(
            $this->name(),
            $this->dir($path)
        );
        return $this;
    }

    public function controllerNspace($class)
    {
        $this->_chalk->backend->controller->nspace(
            $this->name(),
            $this->nspace($class)
        );
        return $this;
    }

    public function controllerAll($class)
    {
        $this->_chalk->backend->controller->all(
            [$class, $this->name()]
        );
        return $this;
    }

    public function frontendViewDir()
    {
        $this->_chalk->frontend->view->dir(
            $this->name(),
            $this->_chalk->config->viewDir->dir(Chalk::info($this->nspace())->name)
        );
        return $this;
    }

    public function register($name, $class = null)
    {
        $this->_chalk->register(
            $this->name($name),
            isset($class) ? $this->nspace($class) : 'Chalk\Event'
        );
        return $this;
    }

    public function listen($name, callable $listener)
    {
        $this->_chalk->listen(
            $name,
            $listener instanceof Closure ? $listener->bindTo($this) : $listener
        );
        return $this;
    }
}