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
use Coast\Router;
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

    public function entityDir($nspace, $path = 'lib')
    {
        $this->em
            ->dir($nspace, $this->dir($path));
        return $this;
    }

    public function hookListen($name, Closure $listener)
    {
        $this->hook
            ->listen($name, $listener);
        return $this;
    }

    public function frontendInit()
    {}

    public function frontendControllerNspace($name, $nspace = 'Frontend\Controller')
    {
        $this->frontend->controller
            ->nspace($name, $this->nspace($nspace));
        return $this;
    }

    public function frontendControllerAll($name, $class = 'All')
    {
        $this->frontend->controller
            ->all([$class, $name]);
        return $this;
    }

    public function frontendViewDir($name, $path = 'frontend/views')
    {
        $config = $this->config->viewScripts;
        $this->frontend->view
            ->dir("__Chalk__{$name}", $this->dir($path))
            ->extension($config[1], "{$config[0]}/{$name}", [null, "__Chalk__{$name}"]);
        return $this;
    }

    public function frontendUrlResolver($name, Closure $resolver)
    {
        $this->frontend->url
            ->resolver($name, $resolver);
        return $this;
    }

    public function frontendRoute($name, $method = Router::METHOD_ALL, $path, $params)
    {
        $this->frontend->router
            ->route($name, $method, $path, $params);
        return $this;
    }

    public function frontendHookListen($name, Closure $listener)
    {
        $this->frontend->hook
            ->listen($name, $listener);
        return $this;
    }

    public function backendInit()
    {}

    public function backendControllerNspace($name, $nspace = 'Backend\Controller')
    {
        $this->backend->controller
            ->nspace($name, $this->nspace($nspace));
        return $this;
    }

    public function backendControllerAll($name, $class = 'All')
    {
        $this->backend->controller
            ->all([$class, $name]);
        return $this;
    }

    public function backendViewDir($name, $path = 'backend/views')
    {
        $this->backend->view
            ->dir($name, $this->dir($path));
        return $this;
    }

    public function backendHookListen($name, Closure $listener)
    {
        $this->backend->hook
            ->listen($name, $listener);
        return $this;
    }

    public function backendRoute($name, $method = Router::METHOD_ALL, $path, $params)
    {
        $this->backend->router
            ->route($name, $method, $path, $params);
        return $this;
    }
}