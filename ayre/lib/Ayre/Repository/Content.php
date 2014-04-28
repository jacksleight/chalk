<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Repository;

use Ayre\Repository;

class Content extends Repository
{
	public function fetchAll($criteria = array(), $sort = null, $page = null)
	{
		$criteria = $criteria + [
			'search'		=> null,
			'createDateMin'	=> null,
			'createUsers'	=> [],
			'statuses'		=> [],
		];

		$params = ['classes' => [$this->_entityName]];
		$qb = $this->createQueryBuilder('c')
			->andWhere("c INSTANCE OF :classes")
			->andWhere("c.next IS NULL");
		
		if (isset($sort)) {

		} else {
			$qb->addOrderBy("c.modifyDate", "DESC");
		}

		if (isset($criteria['search'])) {
			$results = $this->_em->getRepository('Ayre\Entity\Index')
				->search($criteria['search'], $this->_class->name);
			$ids = \Coast\array_column($results, 'entity_id');
			$qb ->andWhere("c.id IN (:ids)")
				->addSelect("FIELD(c.id, :ids) AS HIDDEN sort")
				->orderBy("sort");
			$params['ids'] = $ids;
		}
		if (isset($criteria['createDateMin'])) {
			$qb->andWhere("c.createDate >= :createDateMin");
			$params['createDateMin'] = new \DateTime("{$criteria['createDateMin']}");
		}
		if (isset($criteria['createDateMax'])) {
			$qb->andWhere("c.createDate <= :createDateMax");
			$params['createDateMax'] = new \DateTime("{$criteria['createDateMax']}");
		}
		if (isset($criteria['modifyDateMin'])) {
			$qb->andWhere("c.modifyDate >= :modifyDateMin");
			$params['modifyDateMin'] = new \DateTime("{$criteria['modifyDateMin']}");
		}
		if (isset($criteria['modifyDateMax'])) {
			$qb->andWhere("c.modifyDate <= :modifyDateMax");
			$params['modifyDateMax'] = new \DateTime("{$criteria['modifyDateMax']}");
		}
		if (count($criteria['createUsers'])) {
			$qb->andWhere("c.createUser IN (:createUsers)");
			$params['createUsers'] = $criteria['createUsers'];
		}
		if (count($criteria['statuses'])) {
			$qb->andWhere("c.status IN (:statuses)");
			$params['statuses'] = $criteria['statuses'];
		}

		return $qb
			->getQuery()
			->setParameters($params)
			->getResult();
	}

	public function fetchAllForPublish()
	{
		return $this->_em->createQueryBuilder()
			->select("c")
			->from($this->_class->name, "c")
			->where("c.status IN (:statuses)")
			->addOrderBy("c.master")
			->addOrderBy("c.version", "DESC")
			->getQuery()
			->setParameters([
				'statuses' => [
					\Ayre::STATUS_PENDING,
					\Ayre::STATUS_PUBLISHED,
				],
			])
			->getResult();
	}
}