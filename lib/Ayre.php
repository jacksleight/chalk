<?php
use Coast\App;

class Ayre extends App
{
	const STATUS_DRAFT		= 'draft';
	const STATUS_PENDING	= 'pending';
	const STATUS_PUBLISHED	= 'published';
	const STATUS_ARCHIVED	= 'archived';

	protected static $_blameable;
	protected static $_types = [];

	public static function blameable($blameable = null)
	{
		if (isset($blameable)) {
			self::$_blameable = $blameable;
		}
		return self::$_blameable;
	}

	public static function resolve($class)
	{
		if (is_object($class)) {
			$class = get_class($class);
		}
		if (isset(self::$_types[$class])) {
			return self::$_types[$class];
		}

		$namespace = __CLASS__ . '\\Entity';
		if (!is_subclass_of($class, $namespace)) {
			throw new Exception("Class {$class} does not extend {$namespace}");   
		}

		$short = str_replace($namespace . '\\', '', $class);
		$parts = explode('\\', $short);
		$parts = array_map('lcfirst', $parts);
		array_unshift($parts, 'core');
		self::$_types[$class] = (object) [
			'class'	=> $class,
			'short'	=> $short,
			'id'	=> implode('_', $parts),
			'slug'	=> implode('-', $parts),
			'path'	=> implode('/', $parts),
		];

		return self::$_types[$class];
	}

	public static function resolveShort($short)
	{
		$namespace = __CLASS__ . '\\Entity';
		return self::resolve("{$namespace}\\{$short}");
	}

	public function __construct(array $envs = array())
	{
		parent::__construct($envs);
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
		foreach ($this->_publishables as $class => $type) {
			$entitys = $this->em->getRepository($class)->fetchAllForPublish();
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