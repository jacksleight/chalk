<?php
use Coast\App;

class Ayre extends App
{
	const FORMAT_DATE		= 'jS F Y';

	const STATUS_PENDING	= 'pending';
	const STATUS_PUBLISHED	= 'published';
	const STATUS_ARCHIVED	= 'archived';

	protected static $_modules	= ['Ayre'];
	protected static $_classes	= [];
	protected static $_types	= [];
	protected static $_slugs	= [];
	protected static $_paths	= [];

	protected static $_publishables	= [
		'Ayre\Entity\Content',
	];

	protected $_user;

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
		if (!is_subclass_of($class, 'Ayre\\Entity')) {
			throw new Exception("Class '{$class}' does not extend Ayre\Entity");   
		}

		$module = null;
		foreach (self::$_modules as $name) {
			if (preg_match("/^{$name}\\\/", $class, $match)) {
				$module = $name;
				break;
			}
		}
		if (!isset($module)) {
			throw new Exception("Class '{$class}' is not part of a registered module");   
		}

		$module			= explode('\\', $module);
		$moduleLast		= [$module[count($module) - 1]];
		if ($moduleLast == ['Ayre']) {
			$moduleLast = ['Core'];
		}
		$local			= array_slice(explode('\\', $class), count($module) + 1);
		$entity			= array_merge($moduleLast, $local);
		$modulelower	= array_map('lcfirst', $moduleLast);
		$locallower		= array_map('lcfirst', $local);
		$entitylower	= array_map('lcfirst', $entity);

		$type = implode('_', $entitylower);
		$slug = implode('-', $entitylower);
		$path = implode('/', $entitylower);
		$info = [
			'singular'	=> $local[count($local) - 1],
			'plural'	=> $local[count($local) - 1] . 's',
		];
		self::$_types[$type] = $class;
		self::$_slugs[$slug] = $class;
		self::$_paths[$path] = $class;
		return self::$_classes[$class] = (object) ([
			'class' => $class,
			'type'	=> $type,
			'slug'	=> $slug,
			'path'	=> $path,
			'module' => (object) [
				'class' => implode('\\', $module),
				'type'	=> implode('_', $modulelower),
				'slug'	=> implode('-', $modulelower),
				'path'	=> implode('/', $modulelower),
			],
			'local' => (object) [
				'class' => implode('\\', $local),
				'type'	=> implode('_', $locallower),
				'slug'	=> implode('-', $locallower),
				'path'	=> implode('/', $locallower),
			],
		] + (isset($class::$info) ? $class::$info + $info : $info));
	}

	public function __construct(array $envs = array())
	{
		parent::__construct($envs);
	}

	public function user(\Ayre\Entity\User $user = null)
	{
		if (isset($user)) {
			$this->_user = $user;
			$this->em->blameable()->setUserValue($this->_user);
			return $this;
		}
		return $this->_user;
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

	public function publish()
	{
		foreach (self::$_publishables as $class) {
			$entitys = $this->em($class)->fetchAllForPublish();
			if (is_subclass_of($class, 'Ayre\Behaviour\Versionable')) {
				$last = null;
				foreach ($entitys as $entity) {
					$entity->status = $entity->master === $last
						? Ayre::STATUS_ARCHIVED
						: Ayre::STATUS_PUBLISHED;
					$last = $entity->master;
				}
			} else {
				foreach ($entitys as $entity) {
					$entity->status = Ayre::STATUS_PUBLISHED;
				}
			}
		}
		$this->em->flush();
	}
}