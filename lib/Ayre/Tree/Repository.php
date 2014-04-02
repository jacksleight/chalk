<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre\Tree;

class Repository extends \Doctrine\ORM\EntityRepository
{
	// public function fetchAll()
	// {
	// 	return $this->_em->createQueryBuilder()
	// 		->select("t", "d", "m")
	// 		->from("Ayre\Tree", "t")
	// 			->leftJoin("t.domains" , "d")
	// 			->leftJoin("t.menus" , "m")
	// 		->orderBy("t.name")
	// 		->getQuery()
	// 		->execute();
	// }

	// public function fetchPublished()
	// {
	// 	$statuses = array(
	// 		\Ayre\Tree\Revision::STATUS_PUBLISHED,
	// 	);
	// 	return $this->_em->createQueryBuilder()
	// 		->select("t", "r")
	// 		->from("Ayre\Tree", "t")
	// 			->innerJoin("t.revisions", "r")
	// 		->where("r.status IN('{$statuses[0]}')")
	// 		->orderBy("r.id")
	// 		->getQuery()
	// 		->execute();
	// }

	public function fetchAllForPublish()
	{
		return $this->_em->createQueryBuilder()
			->select("t")
			->from("Ayre\Tree", "t")
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
			->from("Ayre\Tree", "t")
			->innerJoin("t.root", "r")
			->where("t.status IN (:statuses)")
			->getQuery()
			->setParameters([
				'statuses' => [
					\Ayre::STATUS_DRAFT,
					\Ayre::STATUS_PENDING,
				],
			])
			->getResult();
	}

	// public function fetchNodesWithPublishedSilts(\Ayre\Tree\Revision $revision)
	// {
	// 	$statuses = array(
	// 		\Ayre\Tree\Revision::STATUS_PUBLISHED,
	// 	);
	// 	$qb = $this->_em->createQueryBuilder()
	// 		->select("n", "i", "r", "v", "p")
	// 		->from("Ayre\Tree\Revision\Node", "n")
	// 			->leftJoin("n.silt", "i")
	// 				->leftJoin("i.revisions", "r", "r.status IN('{$statuses[0]}')")
	// 					->leftJoin("r.versions", "v")
	// 			->leftJoin("n.paths", "p")
	// 		->addOrderBy("r.id")
	// 		->addOrderBy("v.id")
	// 		->addOrderBy("p.id", "DESC");
		
	// 	$nsm = \Ayre\Tree\Revision\Node::getNsm();
	// 	$nsm->getConfiguration()->setBaseQueryBuilder($qb);
	// 	$nodes = $nsm->fetchTreeAsArray($revision->root);
	// 	$nsm->getConfiguration()->resetBaseQueryBuilder();

	// 	return $nodes;
	// }

	// public function fetchPaths(\Ayre\Tree\Revision $revision)
	// {
	// 	return $this->_em->createQueryBuilder()
	// 		->select("p", "n")
	// 		->from("Ayre\Tree\Revision\Node\Path", "p")
	// 			->leftJoin("p.node", "n")
	// 		->where("n.revision = :revision")
	// 		->orderBy("p.id", "DESC")
	// 		->getQuery()
	// 		->setParameters(array('revision' => $revision))
	// 		->execute();
	// }
}