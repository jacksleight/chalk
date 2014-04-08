<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\App;

class Image
{
	public function call($file)
	{
		return $file = !$file instanceof \Coast\File
            ? new \Coast\File("{$file}")
            : $file;
	}

	public function lorempixel($width, $height = null, $category = null, $gray = false)
	{
		$parts = array();
		if ($gray) {
			$parts[] = 'g';
		}
		$parts[] = $width;
		$parts[] = isset($height) ? $height : $width;
		if (isset($category)) {
			$parts[] = $category;
		}
		return new \Coast\Url('http://lorempixel.com/' . implode('/', $parts) . '/');
	}
}