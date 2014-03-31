<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre\User;

class Repository extends \Doctrine\ORM\EntityRepository
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