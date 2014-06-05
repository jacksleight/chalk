<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Core\Repository;

use Ayre\Repository,
	Ayre\Core\Domain,
	Ayre\Core\Menu,
	Ayre\Core\Structure as CoreStructure;

class Structure extends Repository
{
	public function fetchAll(array $criteria = array())
	{
		$structures = parent::fetchAll($criteria);
		usort($structures, function($a, $b) {
			$aClass = get_class($a);
			$bClass = get_class($b);
			return $aClass == $bClass
				? strcmp($a->name, $b->name)
				: strcmp($aClass, $bClass);
		});
		return $structures;
	}

	public function fetchFirst()
	{
		return $this->createQueryBuilder('s')
			->setMaxResults(1)
			->orderBy("s.id")
			->getQuery()
			->getOneOrNullResult();
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

    public function fetchNodes(CoreStructure $structure, $depth = null)
    {
    	$repo = $this->_em->getRepository('Ayre\Core\Structure\Node');
        return $repo->fetchAll([
			'structure'	=> $structure,
			'depth'		=> $depth,
        ]);
    }

    public function fetchTree(CoreStructure $structure, $depth = null)
    {
        $repo = $this->_em->getRepository('Ayre\Core\Structure\Node');
        $nodes = $repo->fetchAll([
			'structure'	=> $structure,
			'depth'		=> $depth,
        ]);
        return [$nodes[0]];
    }
}