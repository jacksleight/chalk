<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre\Menu;

class Repository extends \Doctrine\ORM\EntityRepository
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