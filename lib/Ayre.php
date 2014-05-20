<?php
use Coast\App,
    Ayre\Core,
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

    public static function type($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        } else if (strpos($class, '_') !== false) {
            $class = isset(self::$_types[$class])
                ? self::$_types[$class]
                : null;
        } else if (strpos($class, '-') !== false) {
            $class = isset(self::$_slugs[$class])
                ? self::$_slugs[$class]
                : null;
        } else if (strpos($class, '/') !== false) {
            $class = isset(self::$_paths[$class])
                ? self::$_paths[$class]
                : null;
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
            'singular'  => $local[count($local) - 1],
            'plural'    => $local[count($local) - 1] . 's',
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
        ] + (isset($class::$info) ? $class::$info + $info : $info));
    }

    public function __construct($baseDir, array $envs = array())
    {
        $baseDir = (new Coast\Dir(__DIR__ . '/..'))->toReal();
        parent::__construct($baseDir, $envs);
        $this->module(new Core());
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