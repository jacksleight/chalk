<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Core\Repository;

use Ayre\Repository;

class Content extends Repository
{
	public function fetchByMasterId($id)
	{
		return $this->createQueryBuilder("c")
            ->innerJoin('c.master', 'cm')
            ->andWhere('c.next IS NULL')
			->andWhere("cm.id = :id")
			->setMaxResults(1)
			->getQuery()
			->setParameters([
				'id' => $id,
			])
			->getSingleResult();
	}

	public function fetchAll(array $criteria = array(), $sort = null, $page = null)
	{
		$criteria = $criteria + [
			'search'		=> null,
			'createDateMin'	=> null,
			'createUsers'	=> [],
			'statuses'		=> [],
		];

		$params = [];
		$qb = $this->createQueryBuilder('c');
		
		$qb->andWhere("c.next IS NULL");
		
		if (isset($sort)) {
			$qb->addOrderBy("c.{$sort[0]}", "{$sort[1]}");
		} else {
			$qb->addOrderBy("c.modifyDate", "DESC");
		}

		if (isset($criteria['search'])) {
			if ($this->_class->name == 'Ayre\Core\Content') {
				$classes = $this->_em->getClassMetadata($this->_class->name)->subClasses;
			} else {
				$classes = [$this->_class->name];
			}
			$results = $this->_em->getRepository('Ayre\Core\Index')
				->search($criteria['search'], $classes);
			$ids = \Coast\array_column($results, 'entityId');
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

	public function fetchCountForPublish()
	{
		return $this->_em->createQueryBuilder()
			->select("COUNT(c)")
			->from($this->_class->name, "c")
			->where("c.status IN (:statuses)")
			->getQuery()
			->setParameters([
				'statuses' => [
					\Ayre::STATUS_DRAFT,
					\Ayre::STATUS_PENDING,
				],
			])
			->getSIngleScalarResult();
	}

	public function fetchAllForPublish()
	{
		return $this->_em->createQueryBuilder()
			->select("c")
			->from($this->_class->name, "c")
			->where("c.status IN (:statuses)")
			// ->addOrderBy("c.master")
			// ->addOrderBy("c.version", "DESC")
			->getQuery()
			->setParameters([
				'statuses' => [
					\Ayre::STATUS_DRAFT,
					\Ayre::STATUS_PENDING,
				],
			])
			->getResult();
	}
}