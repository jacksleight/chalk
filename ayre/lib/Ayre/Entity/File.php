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
 * @Gedmo\Uploadable(pathMethod="generatePath", appendNumber=true)
 */
class File extends Content
{
	protected static $_uploadable;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\UploadableFilePath
     */
	protected $path;

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
		$dir	= new \Coast\Dir(self::uploadable()->getDefaultPath());
		$count	= iterator_count($dir->iterator(true));
		$number	= ceil(max(1, $count) / 1000);
		return $dir->dir($number, true)->name();
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
		}
		$dir = new \Coast\Dir(self::uploadable()->getDefaultPath());
		return $dir->file($this->path);
	}

	public function fileName()
	{
		return $this->file()->fileName();
	}

	public function searchFields()
	{
		return array_merge(parent::searchFields(), [
			'fileName',
		]);
	}

	public function isGdCompatible()
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

	public function makePathRelative()
	{
		$dir		= new \Coast\Dir(self::uploadable()->getDefaultPath());
		$file		= new \Coast\File($this->path);
		$this->path	= $file->toRelative($dir);
	}
}