<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Repository;

use Ayre\Repository;

class Menu extends Repository
{
	public function fetchAll()
	{
		return $this->_em->createQueryBuilder()
			->select("m", "t")
			->from("Ayre\Menu", "m")
				->innerJoin("m.tree", "t")
			->addOrderBy("m.name")
			->getQuery()
			->execute();
	}
}