<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Core;
use Chalk\App as Chalk;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 */
class File extends Content
{
	public static $chalkSingular = 'File';
	public static $chalkPlural   = 'Files';
	public static $chalkIcon     = 'image';

	protected static $_baseDir;
	protected static $_mimeTypes = [];
	
    /**
     * @Column(type="string")
     */
	protected $baseName;
	
    /**
     * @Column(type="integer")
     */
	protected $size;

    /**
     * @Column(type="string")
     */
	protected $hash;

	protected $file;

	protected $newFile;

	public function __construct()
	{
		parent::__construct();

		$this->status		= Chalk::STATUS_PUBLISHED;
		$this->publishDate	= new \DateTime();
	}

	public static function baseDir(\Coast\Dir $baseDir = null)
	{
		if (isset($baseDir)) {
			self::$_baseDir = $baseDir;
		}
		return self::$_baseDir;
	}

	public static function mimeTypes(array $mimeTypes = null)
	{
		if (isset($mimeTypes)) {
			self::$_mimeTypes = $mimeTypes;
		}
		return self::$_mimeTypes;
	}

	public function move(\Coast\File $file)
	{
		if (!$file->exists()) {
			throw new \Exception("File '{$file}' does not exist");
		}

		$this->remove();
		
		$this->name(ucwords(trim(preg_replace('/[_]+/', ' ', $file->fileName()))));
		$this->size	= $file->size();
		$this->hash	= $file->hash('md5');

		$extName = strtolower($file->extName());
		if ($extName) {
			foreach (self::$_mimeTypes as $mimeType => $info) {
				if (in_array($extName, $info[1])) {
					$this->subtype	= $mimeType;
					break;
				}
			}
		}
		if (!isset($this->subtype)) {
			$this->subtype = 'application/octet-stream';
		}

		$regex = '/[\/\?<>\\:\*\|":\x00-\x1f\x80-\x9f]/';
		$sanitized = clone $file;
		$sanitized->baseName(preg_replace($regex, '', $file->baseName()));

		$i	 = 0;
		$dir = $this->dir();
		do {
			$temp = clone $sanitized;
			if ($i > 0) {
				$temp->suffix("-{$i}");
			}
			$i++;
		} while ($dir->file($temp->baseName())->exists());
		
		$this->baseName($temp->baseName());
		$this->file	= $file->move($dir, $this->baseName);
		
		return $this;
	}

	public function remove()
	{
		$file = $this->file();
		if (!isset($file)) {
			return $this;
		}

		if ($file->exists()) {
			$file->remove();
		}

		$this->file		= null;
		$this->baseName	= null;
		$this->subtype	= null;
		$this->size		= null;
		$this->hash		= null;

		return $this;
	}

	public function init()
	{
		$this->file = $this->dir()->file($this->baseName);
		return $this;
	}

	public function dir()
	{	
		return self::$_baseDir->dir("{$this->hash[0]}/{$this->hash[1]}", true);
	}

	public static function staticSubtypeLabel($subtype)
	{
		return isset(self::$_mimeTypes[$subtype])
			? self::$_mimeTypes[$subtype][0]
			: 'Unknown Type';
	}

	public function isImage()
	{
		return $this->isImageBitmap() || $this->isImageVector();
	}

	public function isImageBitmap()
	{
		$mimeTypes = [
			'image/gif',
			'image/jpeg',
			'image/png',
			'image/webp',
		];
		return in_array($this->subtype, $mimeTypes);
	}

	public function isImageVector()
	{
		$mimeTypes = [
			'image/svg+xml',
		];
		return in_array($this->subtype, $mimeTypes);
	}

	public function searchableContent()
	{
		return array_merge(parent::searchableContent(), [
			$this->baseName,
		]);
	}
}