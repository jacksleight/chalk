<?php
use Coast\App;

class Ayre extends App
{
	const STATUS_DRAFT		= 'draft';
	const STATUS_PENDING	= 'pending';
	const STATUS_PUBLISHED	= 'published';
	const STATUS_ARCHIVED	= 'archived';

	protected static $_types	= [];

	protected $_mimeTypeMap;
	protected $_contents			= [];
	protected $_publishables	= [];

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
		if (is_object($short)) {
			$short = get_class($short);
		}
		$namespace = __CLASS__ . '\\Entity';
		return self::resolve("{$namespace}\\{$short}");
	}

	public function __construct(array $envs = array())
	{
		parent::__construct($envs);
		$this
			->register('Ayre\Entity\Document')
			->register('Ayre\Entity\File')
			->register('Ayre\Entity\Content')
			->register('Ayre\Entity\Tree');
			// ->addContentType('Ayre\Url')
			// ->addContentType('Ayre\Url\Email')
			// ->addContentType('Ayre\Url\Oembed');
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

	// protected function _getMimeTypeMap()
	// {
	// 	if (!isset($this->_mimeTypeMap)) {
	// 		$file = $this->_dir->getChild('data')
	// 			->getFile('mime-types.txt')
	// 			->open();
	// 		$mimeTypeMap = array();
	// 		while (false !== $line = $file->get()) {
	// 			if (substr($line, 0, 1) == '#') {
	// 				continue;
	// 			}
	// 			list($mimeType, $exts) = preg_split("/\t+/", trim($line));	
	// 			$exts = preg_split("/\s+/", $exts);
	// 			foreach ($exts as $ext) {
	// 				$mimeTypeMap[$ext] = $mimeType;
	// 			}
	// 		}
	// 		$file->close();
	// 		$this->_mimeTypeMap = $mimeTypeMap;
	// 	}		
	// 	return $this->_mimeTypeMap;
	// }
	
	// public function getMimeTypes()
	// {
	// 	return array_unique(array_values($this->_getMimeTypeMap()));
	// }
	
	// public function extToMimeType($ext)
	// {
	// 	$ext = strtolower($ext);
	// 	$mimeTypeMap = $this->_getMimeTypeMap();
	// 	return isset($mimeTypeMap[$ext])
	// 		? $mimeTypeMap[$ext]
	// 		: null;
	// }
	
	public function register($class)
	{
		$type = self::resolve($class);
		if (is_subclass_of($class, 'Ayre\\Entity\\Content')) {
			$this->_contents[$class] = $type;
		}
		if (is_subclass_of($class, 'Ayre\\Behaviour\\Publishable') && !is_subclass_of($class, 'Ayre\\Entity\\Content')) {
			$this->_publishables[$class] = $type;
		}
		return $this;
	}
	
	// public function getContentTypes()
	// {
	// 	return $this->_contents;
	// }
		
	// public function getContentType($class)
	// {
	// 	return $this->_contents[$class];
	// }
	
	// public function getContentTypeById($id)
	// {
	// 	$map = array_combine(
	// 		\JS\array_column($this->_contents, 'id'),
	// 		array_keys($this->_contents)
	// 	);
	// 	return $this->_contents[$map[$id]];
	// }
	
	// public function getContentTypeBySlug($slug)
	// {
	// 	$map = array_combine(
	// 		\JS\array_column($this->_contents, 'slug'),
	// 		array_keys($this->_contents)
	// 	);
	// 	return $this->_contents[$map[$slug]];
	// }
	
	// public function getContentTypeByObject($content)
	// {
	// 	if ($content instanceof \JS\Entity\Wrapper\Entity) {
	// 		$content = $content->getObject();
	// 	}
	// 	$class = get_class($content);
	// 	return $this->_contents[$class];
	// }
	
	// public function getLayouts()
	// {
	// 	$layouts = array();
	// 	$dir = $this->getRootDir()->getChild('views/_layouts');
	// 	foreach ($dir->getIterator() as $file) {
	// 		$filename = $file->toString(\JS\Path::FILENAME);
	// 		if (preg_match('/page_(.*)/i', $filename, $match)) {
	// 			$layout = new \App\Layout();
	// 			$layout->fromArray(array(
	// 				'id'	=> $match[1],
	// 				'name'	=> ucwords(str_replace('-', ' ', $match[1])),
	// 			));
	// 			$layouts[$layout->name] = $layout;
	// 		}
	// 	}
	// 	ksort($layouts);
	// 	return $layouts;
	// }

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