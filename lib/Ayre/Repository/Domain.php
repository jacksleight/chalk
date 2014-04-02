<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Repository;

use Ayre\Repository;

class Domain extends Repository
{
	public function fetchAll()
	{
		return $this->_em->createQueryBuilder()
			->select("d", "l", "t")
			->from("Ayre\Domain", "d")
				->innerJoin("d.locales", "l")
				->innerJoin("d.tree", "t")
			->addOrderBy("d.name")
			->addOrderBy("l.id")
			->getQuery()
			->execute();
	}
}