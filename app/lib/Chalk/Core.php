<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Chalk,
	Chalk\Module\Basic;

class Core extends Basic
{
	public function init(Chalk $chalk)
	{
		$chalk
			->contentClass('Chalk\Core\Page')
			->contentClass('Chalk\Core\File');
	}
}