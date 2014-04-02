<?php
use Coast\App;

class Ayre extends App
{
	const STATUS_DRAFT		= 'draft';
	const STATUS_PENDING	= 'pending';
	const STATUS_PUBLISHED	= 'published';
	const STATUS_ARCHIVED	= 'archived';

	protected $_mimeTypeMap;

	protected $_silts			= [];
	protected $_publishables	= [];

	public function __construct(array $envs = array())
	{
		parent::__construct($envs);
		$this
			->register('Ayre\Document')
			->register('Ayre\File')
			->register('Ayre\Silt')
			->register('Ayre\Tree');
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
	
	public function register($class)
	{
		$parts = explode('\\', $class);
		if ($parts[0] != 'Ayre') {
            throw new Exception("Class {$class} is not under the Ayre namespace");   
        }
        array_shift($parts);

		$parts = array_map('lcfirst', $parts);
		$type = [
			'class'	=> $class,
			'id'	=> implode('_', $parts),
			'slug'	=> implode('-', $parts),
			'dir'	=> implode('/', $parts),
		];

		if (is_subclass_of($class, 'Ayre\Silt')) {
			$this->_silts[$class] = $type;
		}
		if (is_subclass_of($class, 'Ayre\Behaviour\Publishable') && !is_subclass_of($class, 'Ayre\Silt')) {
			$this->_publishables[$class] = $type;
		}

		return $this;
	}
	
	// public function getSiltTypes()
	// {
	// 	return $this->_silts;
	// }
		
	// public function getSiltType($class)
	// {
	// 	return $this->_silts[$class];
	// }
	
	// public function getSiltTypeById($id)
	// {
	// 	$map = array_combine(
	// 		\JS\array_column($this->_silts, 'id'),
	// 		array_keys($this->_silts)
	// 	);
	// 	return $this->_silts[$map[$id]];
	// }
	
	// public function getSiltTypeBySlug($slug)
	// {
	// 	$map = array_combine(
	// 		\JS\array_column($this->_silts, 'slug'),
	// 		array_keys($this->_silts)
	// 	);
	// 	return $this->_silts[$map[$slug]];
	// }
	
	// public function getSiltTypeByObject($silt)
	// {
	// 	if ($silt instanceof \JS\Entity\Wrapper\Entity) {
	// 		$silt = $silt->getObject();
	// 	}
	// 	$class = get_class($silt);
	// 	return $this->_silts[$class];
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