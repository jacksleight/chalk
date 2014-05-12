<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Repository;

use Ayre\Repository,
	Ayre\Entity\Structure as EntityStructure;

class Structure extends Repository
{
	public function fetch($id)
	{
		if (!isset($id)) {
			return;
		}
		return $this->createQueryBuilder('e')
			->addSelect("n", 'c', 'cv')
			->innerJoin("e.nodes", "n")
			->innerJoin('n.contentMaster', 'c')
			->innerJoin('c.versions', 'cv')
			->andWhere("e.id = :id")
			->andWhere("n.parent IS NULL")
			->andWhere('cv.next IS NULL')
			->getQuery()
			->setParameters(['id' => $id])
			->getSingleResult();
	}

	public function fetchAllForPublish()
	{
		return $this->createQueryBuilder("s")
			->where("s.status IN (:statuses)")
			->addOrderBy("s.master")
			->addOrderBy("s.version", "DESC")
			->getQuery()
			->setParameters([
				'statuses' => [
					\Ayre::STATUS_PENDING,
					\Ayre::STATUS_PUBLISHED,
				],
			])
			->getResult();
	}

	// @todo merge into fetchAll
	public function fetchAllForSlugRefresh()
	{
		return $this->createQueryBuilder("s")
			->where("s.status IN (:statuses)")
			->getQuery()
			->setParameters([
				'statuses' => [
					\Ayre::STATUS_PENDING,
				],
			])
			->getResult();
	}

    public function fetchNodes(EntityStructure $structure, $depth = null)
    {
        return $this->_em->getRepository('Ayre\Entity\Structure\Node')->fetchAll([
			'structure'	=> $structure,
			'include'	=> true,
			'depth'		=> $depth,
        ]);
    }

    public function fetchTree(EntityStructure $structure, $depth = null)
    {
        $this->_em->getRepository('Ayre\Entity\Structure\Node')->fetchAll([
			'structure'	=> $structure,
			'include'	=> true,
			'depth'		=> $depth,
        ]);
        return [$structure->root];
    }
}