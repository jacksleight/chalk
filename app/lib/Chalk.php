<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Coast\App;
use Chalk\Module;
use Chalk\Event;
use Coast\Request;
use Coast\Response;

class Chalk extends App
{
    const VERSION           = '0.5.0';

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
            $class  = "{$nspace}" . (count($parts) ? '\\' . implode('\\', $parts) : null);
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
            'module' => [
                'class' => implode('\\', $nspace),
                'name'  => implode('_', $aliasLcFirst),
                'path'  => implode('/', $aliasLcSplit),
            ],
            'local' => [
                'class' => implode('\\', $local),
                'name'  => implode('_', $localLcFirst),
                'path'  => implode('/', $localLcSplit),
            ],
            'singular'  => class_exists($class) && isset($class::$chalkSingular) ? $class::$chalkSingular : implode('_', $entityLcFirst),
            'plural'    => class_exists($class) && isset($class::$chalkPlural)   ? $class::$chalkPlural   : implode('_', $entityLcFirst),
            'icon'      => class_exists($class) && isset($class::$chalkIcon)     ? $class::$chalkIcon     : 'content',
            'isNode'    => class_exists($class) && isset($class::$chalkIsNode)   ? $class::$chalkIsNode   : false,
            'isUrl'     => class_exists($class) && isset($class::$chalkIsUrl)    ? $class::$chalkIsUrl    : false,
        ]);
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

    public function module($name)
    {
        if ($name instanceof Module) {
            $this->_modules[$name->name()] = $name;
            self::$_map[$name->name()] = $name->nspace();
            $name->app($this);
            $name->init();
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

    public function execute(Request $req = null, Response $res = null)
    {
        throw new \Exception('Chalk app cannot be executed directly, use Frontend or Backend');
    }
}