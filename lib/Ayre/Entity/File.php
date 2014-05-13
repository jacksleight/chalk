<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity;

use Ayre\Entity,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 */
class File extends Content
{
	protected static $_baseDir;
	protected static $_mimeTypes = [];
	
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

		$i	 = 0;
		$dir = $this->dir();
		do {
			$temp = clone $file;
			if ($i > 0) {
				$temp->suffix("-{$i}");
			}
			$i++;
		} while ($dir->file($temp->baseName())->exists());
		
		$this->name($temp->baseName());
		$this->file	= $file->move($dir, $this->name);
		
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
		$this->name		= null;
		$this->subtype	= null;
		$this->size		= null;
		$this->hash		= null;

		return $this;
	}

	public function init()
	{
		$this->file = $this->dir()->file($this->name);
		return $this;
	}

	public function dir()
	{	
		return self::$_baseDir->dir("{$this->hash[0]}/{$this->hash[1]}", true);
	}

	public function subtypeLabel()
	{	
		return isset(self::$_mimeTypes[$this->subtype])
			? self::$_mimeTypes[$this->subtype][0]
			: strtoupper($this->file->extName()) . ' File';
	}

	public function subtypeLabelShort()
	{	
		return isset(self::$_mimeTypes[$this->subtype])
			? self::$_mimeTypes[$this->subtype][1][0]
			: strtoupper($this->file->extName());
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
		return in_array($this->subtype, $mimeTypes);
	}
}