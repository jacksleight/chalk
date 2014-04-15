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
 * @Gedmo\Uploadable(pathMethod="generatePath", callback="updateProperties", appendNumber=true)
 */
class File extends Content
{
	protected static $_uploadable;

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

	public static function uploadable($uploadable = null)
	{
		if (isset($uploadable)) {
			self::$_uploadable = $uploadable;
		}
		return self::$_uploadable;
	}
	
	public function generatePath()
	{
		$dir	= new \Coast\Dir('public/data/store/files', true);
		$count	= iterator_count($dir->iterator(true));
		$number	= ceil(max(1, $count) / 1000);
		return $dir->dir($number, true)->name();
	}
	
	public function updateProperties(array $info)
	{
		$this->path($info['filePath']);
		$this->size($info['fileSize']);
		$this->mimeType($info['fileMimeType']);
	}

	public function path($path = null)
	{
		if (isset($path)) {
			$this->path = $path;
			$this->file()->name($this->path);
			return $this;
		}
		return $this->path;
	}

	public function file(\Coast\File $file = null)
	{
		if (isset($file)) {
			$this->name = ucwords(trim(preg_replace('/[^\w]+/', ' ', $file->fileName())));
			self::uploadable()->addEntityFileInfo($this, new File\Info([
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

	public function mimeType($mimeType = null)
	{
		if (isset($mimeType)) {
			$this->mimeType	= $mimeType;
			$this->subtype	= $this->mimeType;
			return $this;
		}
		return $this->mimeType;
	}

	public function baseName($baseName = null)
	{
		$file = $this->file();
		if (isset($baseName)) {
			$file->rename(['baseName' => $baseName]);
			$this->path = $file->name();
			return $this;
		}
		return $file->baseName();
	}

	public function fileName($fileName = null)
	{
		$file = $this->file();
		if (isset($fileName)) {
			$file->rename(['fileName' => $fileName]);
			$this->path = $file->name();
			return $this;
		}
		return $file->fileName();
	}

	public function extName($extName = null)
	{
		$file = $this->file();
		if (isset($extName)) {
			$file->rename(['extName' => $extName]);
			$this->path = $file->name();
			return $this;
		}
		return $file->extName();
	}
	
	public function searchFields()
	{
		return array_merge(parent::searchFields(), [
			'fileName',
		]);
	}

	public function isImage()
	{
		$info = array_filter(gd_info(), function($a) {
			return $a === true;
		});
		$mimeTypes = array_intersect_key([
			'GIF Create Support'	=> 'image/gif',
			'JPEG Support'			=> 'image/jpeg',
			'PNG Support'			=> 'image/png',
			'WebP Support'			=> 'image/webp',
		], $info);
		return in_array($this->mimeType, $mimeTypes);
	}
}