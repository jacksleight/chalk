<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre\Domain;

class Repository extends \Doctrine\ORM\EntityRepository
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