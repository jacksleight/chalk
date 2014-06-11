<?php
use Coast\App,
    Ayre\Module;

class Ayre extends App
{
    const FORMAT_DATE       = 'jS F Y';

    const STATUS_PENDING    = 'pending';
    const STATUS_PUBLISHED  = 'published';
    const STATUS_ARCHIVED   = 'archived';

    protected static $_namespaces   = [];
    protected static $_classes      = [];
    protected static $_types        = [];
    protected static $_slugs        = [];
    protected static $_paths        = [];

    // protected static $_publishables  = [
    //  'Ayre\Core\Content',
    // ];

    protected $_modules = [];

    protected $_contentClasses = [];

    protected $_styles = [];
    
    protected $_widgets = [];

    public static function type($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        } else if (strpos($class, '\\') === false) {
            $parts = preg_split('/[_\-\/]/', $class);
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

        $alias       = [$alias];       
        $namespace   = explode('\\', $namespace);
        $local       = array_slice(explode('\\', $class), count($namespace));
        $entity      = array_merge($alias, $local);
        $moduleLower = array_map('lcfirst', $alias);
        $localLower  = array_map('lcfirst', $local);
        $entityLower = array_map('lcfirst', $entity);

        $name = implode('_', $entityLower);
        $slug = implode('-', $entityLower);
        $path = implode('/', $entityLower);
        $info = [
            'singular'  => $name,
            'plural'    => $name,
        ];
        self::$_types[$name] = $class;
        self::$_slugs[$slug] = $class;
        self::$_paths[$path] = $class;
        return self::$_classes[$class] = (object) ([
            'class' => $class,
            'name'  => $name,
            'slug'  => $slug,
            'module' => (object) [
                'class' => implode('\\', $namespace),
                'name'  => implode('_', $moduleLower),
                'slug'  => implode('-', $moduleLower),
            ],
            'entity' => (object) [
                'class' => implode('\\', $local),
                'name'  => implode('_', $localLower),
                'slug'  => implode('-', $localLower),
                'path'  => implode('/', $localLower),
                'var'   => lcfirst(implode('', $local)),
            ],
        ] + $class::$info + $info);
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

    public function module($name, $module = null)
    {
        if ($name instanceof Module) {
            $module = $name;
            $parts  = explode('\\', get_class($module));           
            $name   = lcfirst($parts[count($parts) - 1]);
        }
        if (isset($module)) {
            if (!$module instanceof Module) {
                throw new Ayre\Exception("Module must be an instance of Ayre\Module");
            }
            $this->_modules[$name]    = $module;
            self::$_namespaces[$name] = get_class($module);
            $this->view
                ->baseDir($name, $module->viewDir());
            $this->controller
                ->classNamespace($name, $module->controllerNamespace());
            $this->em->getConfiguration()->getMetadataDriverImpl()
                ->addPaths([$name => $module->libDir()]);
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

    public function styles(array $styles = null)
    {
        if (isset($styles)) {
            $this->_styles = array_merge($this->_styles, $styles);
            return $this;
        }
        return $this->_styles;
    }

    public function widgets(array $widgets = null)
    {
        if (isset($widgets)) {
            $this->_widgets = array_merge($this->_widgets, $widgets);
            return $this;
        }
        return $this->_widgets;
    }

    public function contentClass($contentClass)
    {
        $this->_contentClasses[] = $contentClass;
        return $this;
    }

    public function contentClasses()
    {
        return $this->_contentClasses;
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
        $dir = $this->root->dir('views/layouts/page');
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

    // public function publish()
    // {
    //  foreach (self::$_publishables as $class) {
    //      $entitys = $this->em($class)->fetchAllForPublish();
    //      if (is_subclass_of($class, 'Ayre\Behaviour\Versionable')) {
    //          $last = null;
    //          foreach ($entitys as $entity) {
    //              $entity->status = $entity->master === $last
    //                  ? Ayre::STATUS_ARCHIVED
    //                  : Ayre::STATUS_PUBLISHED;
    //              $last = $entity->master;
    //          }
    //      } else {
    //          foreach ($entitys as $entity) {
    //              $entity->status = Ayre::STATUS_PUBLISHED;
    //          }
    //      }
    //  }
    //  $this->em->flush();
    // }
}