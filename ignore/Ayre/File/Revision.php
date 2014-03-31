<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre\File;

class Revision extends \Ayre\Item\Revision
{
	protected static $_imagickCompatibleMimeTypes;

	protected $mimeType;
	protected $fileName;
	protected $file;
	protected $newFile;

	protected static function _defineMetadata($class)
	{
		return array_merge_recursive(parent::_defineMetadata($class), array(
			'fields' => array(
				'mimeType' => array(
					'type'		=> 'string',
					'length'	=> 255,
				),
				'fileName' => array(
					'type'		=> 'string',
					'length'	=> 255,
					'validator'	=> new \Js\Validator\Chain(array(
						new \Js\Validator\Regex('/^[a-z0-9\.\-_ ]+$/i'),
					)),	
				),
			),
		));
	}
		
	protected static function _getImagickCompatibleMimeTypes()
	{
		if (!isset(self::$_imagickCompatibleMimeTypes)) {
			$im = new \JS\Imagick();
			$formats = $im->queryFormats();
			$im->destroy();
			$formatMap = array(
				'GIF'	=> 'image/gif',
				'JPEG'	=> 'image/jpeg',
				'PDF'	=> 'application/pdf',
				'PNG'	=> 'image/png',
				'SVG'	=> 'image/svg+xml',
				'WEBP'	=> 'image/webp',
			);
			foreach ($formatMap as $format => $mimeType) {
				if (in_array($format, $formats)) {
					$imagickCompatibleMimeTypes[] = $mimeType;
				}
			}
			self::$_imagickCompatibleMimeTypes = $imagickCompatibleMimeTypes;
		}		
		return self::$_imagickCompatibleMimeTypes;
	}

	public function getDir()
	{
		$num = ceil($this->id / 1000);
		return \Ayre::$instance->dir("store/files/{$num}", true, 0777);
	}
			
	public function isReadable()
	{
		return isset($this->file) && $this->file->isFile() && $this->file->isReadable();
	}

	public function isImagickCompatible()
	{
		$imagickCompatibleMimeTypes = self::_getImagickCompatibleMimeTypes();
		return in_array($this->mimeType, $imagickCompatibleMimeTypes);
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
	
	public function setMimeType($mimeType)
	{
		$this->mimeType = $mimeType;
		$this->item->subtype = $mimeType;
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
	
	public function postPersistUpdate()
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

	public function postLoad()
	{
		parent::postLoad();

		$this->file = $this->getDir()->getFile($this->fileName);
	}

	public function _postValidate()
	{
		parent::_postValidate();

		if ($this->isPersisted()) {
			$path		= new \JS\Path($this->fileName);
			$extension	= $path->toString(\JS\Path::EXTENSION);
			if (\Ayre::$instance->app->extToMimeType($extension) != $this->mimeType) {
				$this->_addError('fileName', 'validator_extension_invalid');
			}
		}
	}	
}