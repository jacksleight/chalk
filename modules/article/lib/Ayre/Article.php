<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

use Ayre,
	Ayre\Module\Basic;

class Article extends Basic
{
	public function init(Ayre $ayre)
	{
		$ayre
			->contentClass('Ayre\Article\Article');
	}
}