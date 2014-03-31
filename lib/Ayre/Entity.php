<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre;

class Entity
{
	public function __set($name, $value)
	{
		$this->$name = $value;
		return $this;
	}

	public function __get($name)
	{
		return $this->$name;
	}
}