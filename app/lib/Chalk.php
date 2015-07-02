<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Coast\App;
use Chalk\Core;
use Closure;
use Chalk\Module;
use Chalk\Event;
use Coast\Request;
use Coast\Response;

class Chalk extends App
{
    const VERSION           = '0.3.8';

    const FORMAT_DATE       = 'jS F Y';

    const STATUS_DRAFT      = 'draft';
    const STATUS_PENDING    = 'pending';
    const STATUS_PUBLISHED  = 'published';
    const STATUS_ARCHIVED   = 'archived';

    protected static $_isFrontend = true;

    protected static $_classes = [];
    protected static $_map     = [];

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

    protected function _preExecute(Request $req = null, Response $res = null)
    {
        self::isFrontend(false);
    }

    protected function _postExecute(Request $req = null, Response $res = null)
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
            if (!isset(self::$_map[$parts[0]])) {
                throw new Exception("Class '{$class}' does not belong to a registered module");
            }
            $nspace = self::$_map[array_shift($parts)];
            $parts  = array_map('ucfirst', $parts);
            $class  = "{$nspace}\\" . implode('\\', $parts);
        }
        if (false !== $pos = strpos($class, '\\__CG__\\')) {
            $class = substr($class, $pos + 8);
        }
        if (isset(self::$_classes[$class])) {
            return self::$_classes[$class];
        }

        $module = null;
        foreach (self::$_map as $alias => $nspace) {
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
        
        return self::$_classes[$class] = \Coast\array_object_smart([
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
            'singular'  => class_exists($class) && isset($class::$chalkSingular) ? $class::$chalkSingular : implode('_', $entityLcFirst),
            'plural'    => class_exists($class) && isset($class::$chalkPlural)   ? $class::$chalkPlural   : implode('_', $entityLcFirst),
            'isNode'    => class_exists($class) && isset($class::$chalkIsNode)   ? $class::$chalkIsNode   : false,
            'isUrl'     => class_exists($class) && isset($class::$chalkIsUrl)    ? $class::$chalkIsUrl    : false,
        ]);
    }

    public function module($name, Module $module = null)
    {
        if (func_num_args() > 1) {
            $this->_modules[$name] = $module;
            self::$_map[$name] = $module->nspace();
            $module->chalk($this, $name);
            $module->init();
            return $this;
        }
        $name = self::info($name)->module->name;
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

    public function register($name, $class = 'Chalk\Event')
    {
        if (!is_a($class, 'Chalk\Event', true)) {
            throw new \Exception("Class '{$class}' is not a subclass of Chalk\Event");
        }
        $this->_events[$name] = [
            'class'     => $class,
            'listeners' => [],
        ];
        return $this;
    }

    public function listen($name, callable $listener)
    {
        if (!isset($this->_events[$name])) {
            return $this;
        }
        $this->_events[$name]['listeners'][] = $listener;
        return $this;
    }

    public function fire($name)
    {
        $args = func_get_args();
        array_shift($args);

        if (!isset($this->_events[$name])) {
            return $this;
        }

        $class = $this->_events[$name]['class'];
        $event = new $class();
        array_unshift($args, $event);
        foreach ($this->_events[$name]['listeners'] as $listener) {
            call_user_func_array($listener, $args);
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

    public function statusClass($status)
    {
        return [
            self::STATUS_DRAFT      => 'negative',
            self::STATUS_PENDING    => 'negative',
            self::STATUS_PUBLISHED  => 'positive badge-out',
            self::STATUS_ARCHIVED   => 'light badge-out',
        ][$status];
    }
}