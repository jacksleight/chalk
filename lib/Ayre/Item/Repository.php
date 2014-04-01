<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre\Item;

class Repository extends \Doctrine\ORM\EntityRepository
{
	public function fetch($item)
	{
		return $this->_createQueryBuilder()
			->andWhere("i = :item")
			->setParameters(array(
				'item' => $item,
			))
			->getQuery()
			->getSingleResult();
	}

	public function fetchAll()
	{
		return $this->_createQueryBuilder()
			->getQuery()
			->getResult();
	}

	public function fetchAllForPublishing()
	{
		return $this->_em->createQueryBuilder()
			->select("i", "r")
			->from("Ayre\Item", "i")
				->innerJoin("i.revisions", "r")
			->where("r.status IN :statuses")
			->orderBy("r.id")
			->getQuery()
			->setParameters(array(
				'statuses' => array(
					\Ayre\Item\Revision::STATUS_PUBLISHED,
					\Ayre\Item\Revision::STATUS_PENDING,
				),
			))
			->execute();
	}

	protected function _createQueryBuilder()
	{
		return $this->_em->createQueryBuilder()
			->select("i", "r", "v", "l")
			->from("Ayre\Item", "i")
				->innerJoin("i.revisions", "r")
					->innerJoin("r.versions", "v")
						->innerJoin("v.locale", "l")
				->leftJoin("i.revisions", "r_temp", "WITH", "r.id < r_temp.id")
			->andWhere("r_temp.id IS NULL")
			->addOrderBy("r.id")
			->addOrderBy("l.id");
	}	
}