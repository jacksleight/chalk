<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Repository;

use Ayre\Repository;

class User extends Repository
{
	public function fetchAll()
	{
		return $this->_em->createQueryBuilder()
			->select("u")
			->from("Ayre\User", "u")
			->addOrderBy("u.name")
			->getQuery()
			->execute();
	}
}