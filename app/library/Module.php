<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk;

use Chalk\Chalk;
use Chalk\Backend;
use Chalk\Frontend;
use Chalk\Module;
use Chalk\Parser\Plugin;
use Chalk\Widget;
use Closure;
use Coast\App\Access;
use Coast\Dir;
use Coast\File;
use Coast\Router;
use Doctrine\Common\EventSubscriber;
use ReflectionClass;

abstract class Module implements Access
{
    const NAME    = null;
    const VERSION = null;

    use Access\Implementation;

    protected $_name;

    protected $_baseDir;

    protected $_options = [];

    protected $_scriptsPath = 'scripts';

    public function __construct($options = null, $baseDir = '..')
    {
        if (!$baseDir instanceof Dir) {
            $reflection = new ReflectionClass(get_class($this));
            $baseDir = (new File($reflection->getFileName()))
                ->dir()
                ->dir("{$baseDir}")
                ->toReal();
    	}
        $this->options($options);
        $this->baseDir($baseDir);
    }

    public function version()
    {
        return static::VERSION;
    }

    public function options($options = null)
    {
        if (isset($options)) {
            $this->_options = \Coast\array_merge_smart($this->_options, $options);
            return $this;
        }
        return $this->_options;
    }

    public function option($name)
    {
        return $this->_options[$name];
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
        $base = static::NAME;
        return isset($name)
            ? $base . '_' . $name
            : $base;
    }

    public function path($path = null)
    {
        $base = strtolower(\Coast\str_camel_split(static::NAME, '-'));
        return isset($path)
            ? $base . '/' . $path
            : $base;
    }

    public function nspace($nspace = null)
    {
        $base = substr($c = get_class($this), 0, strrpos($c, '\\'));
        return isset($nspace)
            ? $base . '\\' . $nspace
            : $base;
    }

    public function dir($path = null, $create = false)
    {
        $base = $this->_baseDir;
        return isset($path)
            ? $base->dir($path, $create)
            : $base;
    }

    public function file($path)
    {
        return $this->_baseDir->file($path);
    }

    public function init()
    {}

    public function entityDir($nspace, $path = 'library')
    {
        $this->em
            ->dir($nspace, $this->dir($path));
        return $this;
    }

    public function entityListener($name, EventSubscriber $listener)
    {
        $this->em
            ->listener($name, $listener);
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

    public function frontendInitSecondary()
    {}

    public function frontendControllerNspace($name, $nspace = 'Frontend\Controller')
    {
        $this->frontend->controller
            ->nspace($name, $this->nspace($nspace));
        return $this;
    }

    public function frontendControllerAll($name, $controller = 'all')
    {
        $this->frontend->controller
            ->all([$controller, $name]);
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

    public function frontendParserPlugin($name, $plugin)
    {
        $this->frontend->parser
            ->plugin($name, $plugin);
        return $this;
    }

    public function frontendResolver($name, Closure $resolver)
    {
        $this->frontend->url
            ->resolver($name, $resolver);
        return $this;
    }

    public function frontendRoute($name, $method = Router::METHOD_ALL, $path, $params = [], \Closure $target = null)
    {
        $this->frontend->router
            ->route($name, $method, $path, $params, $target);
        return $this;
    }

    public function frontendRouteAlias($name, $value)
    {
        $this->frontend->router
            ->alias($name, $value);
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

    public function backendControllerAll($name, $controller = 'all')
    {
        $this->backend->controller
            ->all([$controller, $name]);
        return $this;
    }

    public function backendViewDir($name, $path = 'backend/views')
    {
        $this->backend->view
            ->dir($name, $this->dir($path));
        return $this;
    }

    public function backendParserPlugin($name, $plugin)
    {
        $this->backend->parser
            ->plugin($name, $plugin);
        return $this;
    }

    public function backendResolver($name, Closure $resolver)
    {
        $this->backend->url
            ->resolver($name, $resolver);
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
            ->route($name, $method, $path, ['group' => $this->path()] + $params);
        return $this;
    }

    public function backendRouteAlias($name, $value)
    {
        $this->backend->router
            ->alias($name, $value);
        return $this;
    }

    public function scripts($type)
    {
        $dir = $this->dir("{$this->_scriptsPath}/{$type}");
        if (!$dir->exists()) {
            return [];
        }
        $scripts = [];
        foreach ($dir as $file) {
            $scripts[] = $file->fileName();
        }
        return $scripts;
    }

    public function execScript($type, $name, array $args = [])
    {
        $file = $this->file("{$this->_scriptsPath}/{$type}/{$name}.php");
        if (!$file->exists()) {
            return false;
        }
        $func = require $file;
        $func = $func->bindTo($this);
        call_user_func_array($func, $args);
        return true;
    }

    public function widgetObject($info, $state = null)
    {
        $class = $info->class;
        return new $class($state);
    }

    public function widgetEditView(Widget $widget)
    {
        $info = Chalk::info($widget);
        return [$info->local->path, $info->module->name];
    }

    public function widgetRenderView(Widget $widget)
    {
        $info   = Chalk::info($widget);
        $config = $this->config->viewScripts;
        return ["{$config[0]}/{$info->module->name}/{$info->local->path}", $config[1]];
    }
}