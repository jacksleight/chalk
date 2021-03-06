<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Structure\Node;

use RecursiveArrayIterator;

class Iterator extends RecursiveArrayIterator
{
	public function __construct($array = array(), $flags = 0)
	{
		if (!is_array($array)) {
			$array = $array->toArray();
		}
		parent::__construct($array, $flags);
	}

	public function hasChildren()
	{
		return isset($this->current()->children) && count($this->current()->children);
	}

	public function getChildren()
	{
		return new Iterator($this->current()->children);
	}
}
