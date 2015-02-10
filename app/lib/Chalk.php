<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Coast\App,
    Chalk\Core,
    Closure,
    Chalk\Module;

class Chalk extends App
{
    const VERSION           = '0.3.3';

    const FORMAT_DATE       = 'jS F Y';

    const STATUS_DRAFT      = 'draft';
    const STATUS_PENDING    = 'pending';
    const STATUS_PUBLISHED  = 'published';
    const STATUS_ARCHIVED   = 'archived';

    protected static $_isFrontend = true;

    protected static $_nspaces = [];
    protected static $_classes = [];

    protected $_events  = [];
    protected $_modules = [];

    public static function isFrontend($value = null)
    {
        if (func_num_args() > 0) {
            self::$_isFrontend = $value;
            return null;
        }
        return self::$_isFrontend;
    }

    protected function _preExecute()
    {
        self::isFrontend(false);
    }

    protected function _postExecute()
    {
        self::isFrontend(true);
    }

    public static function info($class)
    {
        if (is_object($class)) {
            if ($class instanceof \stdClass) {
                return $class;
            }
            $class = get_class($class);
        } else if (strpos($class, '\\') === false) {
            $parts = preg_split('/[_\/]/', $class);
            if (!isset(self::$_nspaces[$parts[0]])) {
                throw new Exception("Class '{$class}' does not belong to a registered module");
            }
            $nspace = self::$_nspaces[array_shift($parts)];
            $parts = array_map('ucfirst', $parts);
            $class = "{$nspace}\\" . implode('\\', $parts);
        }
        if (isset(self::$_classes[$class])) {
            return self::$_classes[$class];
        }

        $module = null;
        foreach (self::$_nspaces as $alias => $nspace) {
            if (preg_match("/^". preg_quote($nspace) ."(?:\\\|$)/", $class, $match)) {
                $module = [$alias, $nspace];
                break;
            }
        }
        if (!isset($module)) {
            throw new Exception("Class '{$class}' does not belong to a registered module");   
        }

        $alias         = [$alias];       
        $nspace        = explode('\\', $nspace);
        $local         = array_slice(explode('\\', $class), count($nspace));
        $entity        = array_merge($alias, $local);
        
        $aliasLcFirst  = array_map('lcfirst', $alias);
        $aliasLcSplit  = array_map(function($value) {
            return strtolower(\Coast\str_camel_split($value, '-'));
        }, $alias);
        $localLcFirst  = array_map('lcfirst', $local);
        $localLcSplit  = array_map(function($value) {
            return strtolower(\Coast\str_camel_split($value, '-'));
        }, $local);
        $entityLcFirst = array_map('lcfirst', $entity);
        $entityLcSplit = array_map(function($value) {
            return strtolower(\Coast\str_camel_split($value, '-'));
        }, $entity);
        
        return self::$_classes[$class] = \Coast\array_object_smart(\Coast\array_merge_smart([
            'class' => $class,
            'name'  => implode('_', $entityLcFirst),
            'path'  => implode('/', $entityLcSplit),
            'var'   => lcfirst(implode('', $entity)),
            'module' => [
                'class' => implode('\\', $nspace),
                'name'  => implode('_', $aliasLcFirst),
                'path'  => implode('/', $aliasLcSplit),
                'var'   => lcfirst(implode('', $alias)),
            ],
            'local' => [
                'class' => implode('\\', $local),
                'name'  => implode('_', $localLcFirst),
                'path'  => implode('/', $localLcSplit),
                'var'   => lcfirst(implode('', $local)),
            ],
            'singular'  => implode('_', $entityLcFirst),
            'plural'    => implode('_', $entityLcFirst),
        ], isset($class::$_chalkInfo) ? $class::$_chalkInfo : []));
    }

    public function module($name, Module $module = null)
    {
        if (func_num_args() > 1) {
            $this->_modules[$name] = $module;
            self::$_nspaces[$name] = $module->nspace();
            $module->init($this);
            return $this;
        }
        return isset($this->_modules[$name])
            ? $this->_modules[$name]
            : null;
    }

    public function modules()
    {
        return $this->_modules;
    }

    public function isDebug()
    {
        return (bool) $this->env('DEBUG');
    }

    public function isDevelopment()
    {
        return $this->env('SERVER') == 'development';
    }   

    public function isStaging()
    {
        return $this->env('SERVER') == 'staging';
    }   

    public function isProduction()
    {
        return $this->env('SERVER') == 'production';
    }

    public function frontend()
    {
        return $this->_frontend;
    }

    public function layouts()
    {
        if (!isset($this->config->layoutDir) || !$this->config->layoutDir->exists()) {
            return [];
        }

        $layouts = [];
        $it = $this->config->layoutDir->iterator(null, true);
        foreach ($it as $file) {
            $path   = $file->toRelative($this->config->layoutDir);
            $path   = $path->extName('');
            $name   = trim($path, './');
            $label  = ucwords(str_replace(['-', '/', '_'], [' ', ' – ', ' – '], $name));
            $layouts[$name] = $label;
        }
        unset($layouts['default']);
        ksort($layouts);
        return $layouts;
    }

    public function register($class)
    {
        if (!is_subclass_of($class, 'Chalk\Event')) {
            throw new \Exception("Class '{$class}' is not a subclass of Chalk\Event");
        }
        $this->_events[$class] = [];
        return $this;
    }

    public function listen($class, Closure $listener)
    {
        if (!isset($this->_events[$class])) {
            return $this;
        }
        $this->_events[$class][] = $listener->bindTo($this);
        return $this;
    }

    public function fire($class)
    {
        if (!isset($this->_events[$class])) {
            return $this;
        }
        $event = new $class();
        foreach ($this->_events[$class] as $listener) {
            $listener($event);
        }
        return $event;
    }

    public function publish()
    {
        // foreach (self::$_publishables as $class) {
           $entitys = $this->em('Chalk\Core\Content')->all(['isPublishable' => true]);
           // if (is_subclass_of($class, 'Chalk\Behaviour\Versionable')) {
           //     $last = null;
           //     foreach ($entitys as $entity) {
           //         $entity->status = $entity->master === $last
           //         ? Chalk::STATUS_ARCHIVED
           //         : Chalk::STATUS_PUBLISHED;
           //         $last = $entity->master;
           //     }
           // } else {
           //     foreach ($entitys as $entity) {
           //         $entity->status = Chalk::STATUS_PUBLISHED;
           //     }
           // }
           foreach ($entitys as $entity) {
               $entity->status = Chalk::STATUS_PUBLISHED;
           }
       // }
       $this->em->flush();
   }
}