<?php
use Coast\App,
    Ayre\Module;

class Ayre extends App
{
    const VERSION           = '0.1';

    const FORMAT_DATE       = 'jS F Y';

    const STATUS_DRAFT      = 'draft';
    const STATUS_PENDING    = 'pending';
    const STATUS_PUBLISHED  = 'published';
    const STATUS_ARCHIVED   = 'archived';

    protected static $_namespaces = [];
    protected static $_classes    = [];

    // protected static $_publishables  = [
    //  'Ayre\Core\Content',
    // ];

    protected $_modules        = [];
    protected $_contentClasses = [];
    protected $_widgetClasses  = [];
    protected $_styles         = [];

    public static function entity($class)
    {
        if (is_object($class)) {
            if ($class instanceof \stdClass) {
                return $class;
            }
            $class = get_class($class);
        } else if (strpos($class, '\\') === false) {
            $parts = preg_split('/[_\/]/', $class);
            if (!isset(self::$_namespaces[$parts[0]])) {
                throw new Exception("Class '{$class}' does not belong to a registered module");
            }
            $namespace = self::$_namespaces[array_shift($parts)];
            $parts = array_map('ucfirst', $parts);
            $class = "{$namespace}\\" . implode('\\', $parts);
        }
        if (isset(self::$_classes[$class])) {
            return self::$_classes[$class];
        }

        $module = null;
        foreach (self::$_namespaces as $alias => $namespace) {
            if (preg_match("/^". preg_quote($namespace) ."\\\/", $class, $match)) {
                $module = [$alias, $namespace];
                break;
            }
        }
        if (!isset($module)) {
            throw new Exception("Class '{$class}' does not belong to a registered module");   
        }

        $alias         = [$alias];       
        $namespace     = explode('\\', $namespace);
        $local         = array_slice(explode('\\', $class), count($namespace));
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
        
        return self::$_classes[$class] = (object) ([
            'class' => $class,
            'name'  => implode('_', $entityLcFirst),
            'path'  => implode('/', $entityLcSplit),
            'var'   => lcfirst(implode('', $entity)),
            'module' => (object) [
                'class' => implode('\\', $namespace),
                'name'  => implode('_', $aliasLcFirst),
                'path'  => implode('/', $aliasLcSplit),
                'var'   => lcfirst(implode('', $alias)),
            ],
            'local' => (object) [
                'class' => implode('\\', $local),
                'name'  => implode('_', $localLcFirst),
                'path'  => implode('/', $localLcSplit),
                'var'   => lcfirst(implode('', $local)),
            ],
        ] + $class::$info + [
            'singular'  => implode('_', $entityLcFirst),
            'plural'    => implode('_', $entityLcFirst),
        ]);
    }

    public function __construct($baseDir, array $envs = array())
    {
        parent::__construct($baseDir, $envs);
    }

    public function baseDir(\Coast\Dir $baseDir = null)
    {
        if (isset($baseDir)) {
            $this->_baseDir = $baseDir;
            return $this;
        }
        return $this->_baseDir;
    }

    public function module($name, Module $value = null)
    {
        if (func_num_args() > 1) {
            $this->_modules[$name]    = $value;
            self::$_namespaces[$name] = get_class($value);
            $this->view
                ->dir($name, $value->viewDir());
            $this->controller
                ->classNamespace($name, $value->controllerNamespace());
            $this->em->getConfiguration()->getMetadataDriverImpl()
                ->addPaths([$name => $value->libDir()]);
            $value->init($this);
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

    public function style($value = null)
    {
        $this->_styles[] = $value;
        return $this;
    }

    public function styles(array $styles = null)
    {
        if (isset($styles)) {
            foreach ($styles as $value) {
                $this->style($value);
            }
            return $this;
        }
        return $this->_styles;
    }

    public function contentClass($value = null)
    {
        $key = count($this->_contentClasses);
        foreach ($this->_contentClasses as $i => $contentClass) {
            if (is_subclass_of($value, $contentClass)) {
                $key = $i;
                break;
            }
        }
        $this->_contentClasses[$key] = $value;
        return $this;
    }

    public function contentClasses(array $contentClasses = null)
    {
        if (isset($contentClasses)) {
            foreach ($contentClasses as $value) {
                $this->contentClass($value);
            }
            return $this;
        }
        return $this->_contentClasses;
    }

    public function widgetClass($value = null)
    {
        $this->_widgetClasses[] = $value;
        return $this;
    }

    public function widgetClasses(array $widgetClasses = null)
    {
        if (isset($widgetClasses)) {
            foreach ($widgetClasses as $value) {
                $this->widgetClass($value);
            }
            return $this;
        }
        return $this->_widgetClasses;
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
        return new \Ayre\Frontend($this);
    }

    public function layouts()
    {
        $layouts = [];
        $dir = $this->root->dir('app/views/layouts/page');
        if ($dir->exists()) {
            $it = $dir->iterator(null, true);
            foreach ($it as $file) {
                $path   = $file->toRelative($dir);
                $path   = $path->extName('');
                $name   = trim($path, './');
                $label  = ucwords(str_replace(['-', '/'], [' ', ' – '], $name));
                $layouts[$name] = $label;
            }
            unset($layouts['default']);
            ksort($layouts);
        }
        return $layouts;
    }

    public function publish()
    {
        // foreach (self::$_publishables as $class) {
           $entitys = $this->em('core_content')->fetchAllForPublish();
           // if (is_subclass_of($class, 'Ayre\Behaviour\Versionable')) {
           //     $last = null;
           //     foreach ($entitys as $entity) {
           //         $entity->status = $entity->master === $last
           //         ? Ayre::STATUS_ARCHIVED
           //         : Ayre::STATUS_PUBLISHED;
           //         $last = $entity->master;
           //     }
           // } else {
           //     foreach ($entitys as $entity) {
           //         $entity->status = Ayre::STATUS_PUBLISHED;
           //     }
           // }
           foreach ($entitys as $entity) {
               $entity->status = Ayre::STATUS_PUBLISHED;
           }
       // }
       $this->em->flush();
   }
}