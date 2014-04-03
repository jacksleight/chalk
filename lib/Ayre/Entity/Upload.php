<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity;

class Upload
{
	protected $id;
	protected $name;
	protected $mimeType;
	protected $size;
	protected $chunkSize;
	
	protected $_file;
	
	protected static function _defineMetadata($class)
	{
		return array(
			'table' => 'user',
			'fields' => array(
				'id' => array(
					'type'		=> 'int',
				),
				'name' => array(
					'type'		=> 'string',
				),
				'mimeType' => array(
					'type'		=> 'string',
					'validator'	=> new \Js\Validator\Chain(array(
						new \Js\Validator_List(\Ayre::$instance->app->getMimeTypes()),
					)),
				),
				'size' => array(
					'type'		=> 'int',
				),
				'chunkSize' => array(
					'type'		=> 'int',
					'validator'	=> new \Js\Validator\Chain(array(
						new \Js\Validator_Range(0, self::_getMaxChunkSize()),
					)),
				),
			),
		);
	}
	
	protected static function _getMaxChunkSize()
	{
		return min(
			\JS\str_to_bytes(ini_get('upload_max_filesize')),
			\JS\str_to_bytes(ini_get('post_max_size')),
			\JS\str_to_bytes(ini_get('memory_limit'))
		);
	}
			
	public function __construct($id, $name, $size, $chunkSize)
	{
		$this->id			= $id;
		$this->name			= $name;
		$this->size			= $size;
		$this->chunkSize	= $chunkSize;
	
		$path = new JS_File($this->name);
		$ext  = $path->toString(JS_Path::EXTENSION);
		$this->mimeType = \Ayre::$instance->app->extToMimeType($ext);
				
		$name = sha1(session_id() . $this->id . $this->name . $this->size);
		$this->_file = self::$_helper->dir('temp/upload', true, 0777)
			->getFile($name);
	}
	
	public function isComplete()
	{	
		return $this->_file->getSize() == $this->size;
	}
	
	public function getFile()
	{
		return $this->_file;
	}
	
	public function addChunk($chunk, $append)
	{
		$file = $this->_file->open($append ? 'a' : 'w');
		$file->write($chunk);
		$file->close();
	}
}