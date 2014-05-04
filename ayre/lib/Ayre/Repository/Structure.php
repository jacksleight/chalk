<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Repository;

use Ayre\Repository,
	Ayre\Entity;

class Structure extends Repository
{
	public function fetch($id)
	{
		if (!isset($id)) {
			return;
		}
		return $this->createQueryBuilder('e')
			->addSelect("n")
			->andWhere("e.id = :id")
			->innerJoin("e.root", "n")
			->getQuery()
			->setParameters(['id' => $id])
			->getOneOrNullResult();
	}

	public function fetchAllForPublish()
	{
		return $this->_em->createQueryBuilder()
			->select("t")
			->from("Ayre\Entity\Structure", "t")
			->where("t.status IN (:statuses)")
			->addOrderBy("t.master")
			->addOrderBy("t.version", "DESC")
			->getQuery()
			->setParameters([
				'statuses' => [
					\Ayre::STATUS_PENDING,
					\Ayre::STATUS_PUBLISHED,
				],
			])
			->getResult();
	}

	public function fetchAllForSlugRefresh()
	{
		return $this->_em->createQueryBuilder()
			->select("t", "r")
			->from("Ayre\Entity\Structure", "t")
			->innerJoin("t.root", "r")
			->where("t.status IN (:statuses)")
			->getQuery()
			->setParameters([
				'statuses' => [
					\Ayre::STATUS_PENDING,
				],
			])
			->getResult();
	}

	public function fetchNodes(Entity\Structure $structure, $depth = null)
	{
		return $this->_em->getRepository('Ayre\Entity\Structure\Node')
			->fetchAll($structure->root, true, $depth);
	}

	public function fetchTree(Entity\Structure $structure, $depth = null)
	{
		return $this->_em->getRepository('Ayre\Entity\Structure\Node')
			->fetchTree($structure->root, true, $depth);
	}
}