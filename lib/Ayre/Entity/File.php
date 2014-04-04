<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity;

use Ayre\Entity,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Uploadable(pathMethod="generateDir", appendNumber=true)
 */
class File extends Content
{
	public static $uploadable;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\UploadableFilePath
     */
	protected $path;

	protected $file;

    /**
     * @ORM\Column(type="decimal")
     * @Gedmo\UploadableFileSize
     */
	protected $size;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\UploadableFileMimeType
     */
	protected $mimeType;
	
	public function generateDir()
	{
		$dir = new \Coast\Dir('public/data/store/files', true);
		$count = 0;
		foreach ($dir->iterator(true) as $file) {
			$count++;
		}
		$number = ceil(max(1, $count) / 1000);
		return $dir->dir($number, true)->name();
	}

	public function baseName($baseName = null)
	{
		if (isset($baseName)) {
			$this->file()->rename(['baseName' => $baseName]);
			$this->path = $this->file()->name();
			return $this;
		}
		return $this->file()->baseName;
	}

	public function fileName($fileName = null)
	{
		if (isset($fileName)) {
			$this->file()->rename(['fileName' => $fileName]);
			$this->path = $this->file()->name();
			return $this;
		}
		return $this->file()->fileName;
	}

	public function extName($extName = null)
	{
		if (isset($extName)) {
			$this->file()->rename(['extName' => $extName]);
			$this->path = $this->file()->name();
			return $this;
		}
		return $this->file()->extName;
	}

	public function file(\Coast\File $file = null)
	{
		if (isset($file)) {
			$this->name = ucwords(trim(preg_replace('/[^\w]+/', ' ', $file->fileName())));
			self::$uploadable->addEntityFileInfo($this, new File\Info([
				'tmp_name'	=> $file->name(),
				'name'		=> $file->baseName(),
				'size'		=> $file->size(),
				'type'		=> null,
				'error'		=> 0,
			]));
			return $this;
		} else if (!isset($this->file)) {
			$this->file = new \Coast\File($this->path);
		}
		return $this->file;
	}
}