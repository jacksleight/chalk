<?php
use Coast\App;

class Ayre extends App
{
	const STATUS_DRAFT		= 'draft';
	const STATUS_PENDING	= 'pending';
	const STATUS_PUBLISHED	= 'published';
	const STATUS_ARCHIVED	= 'archived';

	protected $_mimeTypeMap;

	protected $_siltTypes			= [];
	protected $_publishableTypes	= [];

	public function __construct(array $envs = array())
	{
		parent::__construct($envs);
		$this
			->addSiltType('Ayre\Document')
			->addSiltType('Ayre\File')
			->addPublishableType('Ayre\Silt')
			->addPublishableType('Ayre\Tree');
			// ->addSiltType('Ayre\Url')
			// ->addSiltType('Ayre\Url\Email')
			// ->addSiltType('Ayre\Url\Oembed');
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
	
	public function addSiltType($class)
	{
		if (!is_subclass_of($class, 'Ayre\Silt')) {
			throw new Exception("Class '{$class}' is not a subclass of Ayre\Silt");
		}
		$this->_siltTypes[$class] = $this->_parseTypeClass($class);
		return $this;
	}

	public function addPublishableType($class)
	{
		if (!is_subclass_of($class, 'Ayre\Behaviour\Publishable')) {
			throw new Exception("Class '{$class}' is not a subclass of Ayre\Behaviour\Publishable");
		} else if (is_subclass_of($class, 'Ayre\Silt')) {
			throw new Exception("Class '{$class}' is a subclass of Ayre\Silt and does not need to be added");
		}
		$this->_publishableTypes[$class] = $this->_parseTypeClass($class);
		return $this;
	}

	public function _parseTypeClass($class)
	{
		$parts = explode('\\', str_replace(get_class($this) . '\\', null, $class));
		foreach ($parts as $i => $part) {
			$parts[$i] = lcfirst($part);
		}
		return array(
			'class'	=> $class,
			'id'	=> implode('_', $parts),
			'slug'	=> implode('-', $parts),
			'dir'	=> implode('/', $parts),
		);
	}
	
	// public function getSiltTypes()
	// {
	// 	return $this->_siltTypes;
	// }
	
	// public function getSiltTypeMap($relation = null)
	// {
	// 	$map = array();
	// 	foreach ($this->_siltTypes as $class => $type) {
	// 		$class = isset($relation)
	// 			? "{$class}\\{$relation}"
	// 			: "{$class}";
	// 		$map[$type['id']] = $class;
	// 	}
	// 	return $map;
	// }
	
	// public function getSiltType($class)
	// {
	// 	return $this->_siltTypes[$class];
	// }
	
	// public function getSiltTypeById($id)
	// {
	// 	$map = array_combine(
	// 		\JS\array_column($this->_siltTypes, 'id'),
	// 		array_keys($this->_siltTypes)
	// 	);
	// 	return $this->_siltTypes[$map[$id]];
	// }
	
	// public function getSiltTypeBySlug($slug)
	// {
	// 	$map = array_combine(
	// 		\JS\array_column($this->_siltTypes, 'slug'),
	// 		array_keys($this->_siltTypes)
	// 	);
	// 	return $this->_siltTypes[$map[$slug]];
	// }
	
	// public function getSiltTypeByObject($silt)
	// {
	// 	if ($silt instanceof \JS\Entity\Wrapper\Entity) {
	// 		$silt = $silt->getObject();
	// 	}
	// 	$class = get_class($silt);
	// 	return $this->_siltTypes[$class];
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
		foreach ($this->_publishableTypes as $class => $type) {
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