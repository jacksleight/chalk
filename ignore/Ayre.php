<?php
class Ayre extends \Coast\App
{
	public static $instance;
	protected $_mimeTypeMap;
	protected $_itemTypes = array();

	public function __construct(array $envs = array())
	{
		parent::__construct($envs);
		self::$instance = $this;

		$this
			->addItemType('Ayre\Document')
			->addItemType('Ayre\File')
			->addItemType('Ayre\Url')
			->addItemType('Ayre\Url\Email')
			->addItemType('Ayre\Url\Oembed');
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

	protected function _getMimeTypeMap()
	{
		if (!isset($this->_mimeTypeMap)) {
			$file = $this->_dir->getChild('data')
				->getFile('mime-types.txt')
				->open();
			$mimeTypeMap = array();
			while (false !== $line = $file->get()) {
				if (substr($line, 0, 1) == '#') {
					continue;
				}
				list($mimeType, $exts) = preg_split("/\t+/", trim($line));	
				$exts = preg_split("/\s+/", $exts);
				foreach ($exts as $ext) {
					$mimeTypeMap[$ext] = $mimeType;
				}
			}
			$file->close();
			$this->_mimeTypeMap = $mimeTypeMap;
		}		
		return $this->_mimeTypeMap;
	}
	
	public function getMimeTypes()
	{
		return array_unique(array_values($this->_getMimeTypeMap()));
	}
	
	public function extToMimeType($ext)
	{
		$ext = strtolower($ext);
		$mimeTypeMap = $this->_getMimeTypeMap();
		return isset($mimeTypeMap[$ext])
			? $mimeTypeMap[$ext]
			: null;
	}
	
	public function addItemType($class)
	{
		$parts = explode('\\', str_replace(get_class($this) . '\\', null, $class));
		foreach ($parts as $i => $part) {
			$parts[$i] = lcfirst($part);
		}
		$this->_itemTypes[$class] = array(
			'class'	=> $class,
			'id'	=> implode('_', $parts),
			'slug'	=> implode('-', $parts),
			'dir'	=> implode('/', $parts),
		);
		return $this;
	}
	
	public function getItemTypes()
	{
		return $this->_itemTypes;
	}
	
	public function getItemTypeMap($relation = null)
	{
		$map = array();
		foreach ($this->_itemTypes as $class => $type) {
			$class = isset($relation)
				? "{$class}\\{$relation}"
				: "{$class}";
			$map[$type['id']] = $class;
		}
		return $map;
	}
	
	public function getItemType($class)
	{
		return $this->_itemTypes[$class];
	}
	
	public function getItemTypeById($id)
	{
		$map = array_combine(
			\JS\array_column($this->_itemTypes, 'id'),
			array_keys($this->_itemTypes)
		);
		return $this->_itemTypes[$map[$id]];
	}
	
	public function getItemTypeBySlug($slug)
	{
		$map = array_combine(
			\JS\array_column($this->_itemTypes, 'slug'),
			array_keys($this->_itemTypes)
		);
		return $this->_itemTypes[$map[$slug]];
	}
	
	public function getItemTypeByObject($item)
	{
		if ($item instanceof \JS\Entity\Wrapper\Entity) {
			$item = $item->getObject();
		}
		$class = get_class($item);
		return $this->_itemTypes[$class];
	}
	
	public function getLayouts()
	{
		$layouts = array();
		$dir = $this->getRootDir()->getChild('views/_layouts');
		foreach ($dir->getIterator() as $file) {
			$filename = $file->toString(\JS\Path::FILENAME);
			if (preg_match('/page_(.*)/i', $filename, $match)) {
				$layout = new \App\Layout();
				$layout->fromArray(array(
					'id'	=> $match[1],
					'name'	=> ucwords(str_replace('-', ' ', $match[1])),
				));
				$layouts[$layout->name] = $layout;
			}
		}
		ksort($layouts);
		return $layouts;
	}

	public function publishItems()
	{
		$items = $this->_helper->em
			->getRepository('App\Item')
			->fetchAllForPublishing();

		foreach ($items as $item) {
			$i		= 0;
			$last	= $item->revisions->count() - 1;
			foreach ($item->revisions as $revision) {
				$revision->status = $i == $last
					? \App\Item\Revision::STATUS_PUBLISHED
					: \App\Item\Revision::STATUS_ARCHIVED;
				$i++;
			}
		}
	}

	public function publishTrees()
	{
		$trees = $this->_helper->em
			->getRepository('App\Tree')
			->fetchAllForPublishing();

		foreach ($trees as $tree) {
			$i		= 0;
			$last	= $tree->revisions->count() - 1;
			foreach ($tree->revisions as $revision) {
				$revision->status = $i == $last
					? \App\Tree\Revision::STATUS_PUBLISHED
					: \App\Tree\Revision::STATUS_ARCHIVED;
				$i++;
			}
		}
	}

	public function syncPaths()
	{
		$locales = $this->_helper->em
			->getRepository('App\Locale')
			->fetchAll();

		$trees = $this->_helper->em
			->getRepository('App\Tree')
			->fetchPublished();

		foreach ($trees as $tree) {
			$nodes = $this->_helper->em
				->getRepository('App\Tree')
				->fetchNodesWithPublishedItems($tree->revision);
			foreach ($nodes as $node) {
				if ($node->isRoot()) {
					continue;
				}
				$ancestorNodes = array_merge(
					$node->getAncestors(),
					array($node)
				);
				foreach ($locales as $locale) {
					$parts = array();
					foreach ($ancestorNodes as $i => $ancestorNode) {
						if ($ancestorNode->isRoot()) {
							continue;
						}
						$item		= $ancestorNode->getNode()->item;
						$parts[$i]	= "_{$item->id}";
						if (!$item->revisions->count()) {
							continue;
						}
						$version = $item->revision->getVersion($locale);
						if (!isset($version)) {
							continue;
						}
						$slug = $version->smartSlug;
						if (!isset($slug)) {
							continue;
						}
						$parts[$i] = $slug;
					}
					$name = implode('/', $parts);
					foreach ($node->getNode()->paths as $path) {
						if ($path->name == $name && $path->locale->id == $locale->id) {
							$path->node = null;
							$node->getNode()->paths->removeElement($path);
							$this->_helper->em->remove($path);
						}
					}
					
					$path = $node->getNode()->createPath();
					$path->locale = $locale;
					$path->name = $name;
					$this->_helper->em->persist($path);
				}
			}
		}
	}
}