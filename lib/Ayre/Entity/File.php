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
*/
class File extends Content
{
	protected static $_imageMimeTypes;

    /**
     * @ORM\Column(type="string")
     */
	protected $mimeType;

    /**
     * @ORM\Column(type="string")
     */
	protected $fileName;

	protected $file;

	protected $newFile;
		
	protected static function _imageMimeTypes()
	{
		if (!isset(self::$_imageMimeTypes)) {
			$im = new \Imagick();
			$formats = $im->queryFormats();
			$im->destroy();
			$map = array(
				'GIF'	=> 'image/gif',
				'JPEG'	=> 'image/jpeg',
				'PDF'	=> 'application/pdf',
				'PNG'	=> 'image/png',
				'SVG'	=> 'image/svg+xml',
				'WEBP'	=> 'image/webp',
			);
			foreach ($map as $format => $mimeType) {
				if (in_array($format, $formats)) {
					$imageMimeTypes[] = $mimeType;
				}
			}
			self::$_imageMimeTypes = $imageMimeTypes;
		}		
		return self::$_imageMimeTypes;
	}

	public function isImage()
	{
		return in_array($this->mimeType, self::_imageMimeTypes());
	}

	public function dir()
	{
		$num = ceil($this->id / 1000);
		return \Ayre::$instance->dir("store/files/{$num}", true, 0777);
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











	public function setFileName($fileName)
	{
		if ($this->isPersisted() && $fileName == $this->fileName) {
			return;
		}

		$path		= new \JS\Path($fileName);
		$fileName	= $path->toString(\JS\Path::FILENAME);
		$extension	= $path->toString(\JS\Path::EXTENSION);
		$i			= 1;
		do {
			$try = $i == 1
				? "{$fileName}.{$extension}"
				: "{$fileName}_{$i}.{$extension}";
			$exists = \Ayre::$instance->em->getRepository('Ayre\File')
				->checkFileNameExists($try);
			$i++;
		} while ($exists);
		
		$this->fileName = $try;
	}
	
	public function setNewFile(\JS\File $file)
	{
		$this->newFile = $file;

		$fileName	= $file->toString(\JS\Path::FILENAME);
		$baseName	= $file->toString(\JS\Path::BASENAME);
		$extension	= $file->toString(\JS\Path::EXTENSION);

		$this->setFileName($baseName);
		$this->setMimeType(\Ayre::$instance->app->extToMimeType($extension));
		if (!$this->isPersisted()) {
			$this->versions->first()->name = ucwords(trim(preg_replace('/[^a-z0-9]+/i', ' ', $fileName)));
		}
	}
	
	/**
	 * @ORM\PrePersist
	 * @ORM\PreUpdate
	 */
	public function processFile()
	{
		parent::postPersistUpdate();

		if (isset($this->newFile)) {
			if (isset($this->file)) {
				$this->file->delete();
			}
			$this->file = $this->newFile->copy($this->dir, $this->fileName);
		} elseif (isset($this->file) && $this->file->toString(\JS\Path::BASENAME) != $this->fileName) {
			$this->file = $this->file->rename($this->fileName);
		}
	}

	/**
	 * @ORM\PostLoad
	 */
	public function initializeFile()
	{
		$this->file = $this->dir()->file($this->fileName);
	}
}