<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Core;

use Ayre\Core;

/**
 * @Entity
*/
class Menu extends Structure
{
	public static $info = [
		'singular'	=> 'Menu',
		'plural'	=> 'Menus',
	];

	public function label()
	{
		return $this->name . ' ' . \Ayre::type($this)->singular;
	}
}