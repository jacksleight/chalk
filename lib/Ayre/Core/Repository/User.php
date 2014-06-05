<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Core\Repository;

use Ayre\Repository;

class User extends Repository
{
	public function fetchByEmailAddress($emailAddress)
	{
		return $this->createQueryBuilder('e')
			->andWhere("e.emailAddress = :emailAddress")
			->getQuery()
			->setParameters(['emailAddress' => $emailAddress])
			->getOneOrNullResult();
	}
}