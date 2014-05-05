<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity\File;

use Gedmo\Uploadable\FileInfo\FileInfoArray;

class Info extends FileInfoArray
{
	public function __construct(array $info)
	{
		$this->fileInfo = $info;
	}

	public function isUploadedFile()
	{
		return false;
	}
}