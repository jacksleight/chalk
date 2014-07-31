<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Core\Repository;

use Ayre\Repository,
	Ayre\Behaviour\Publishable;

class Content extends Repository
{
	use Publishable\Repository {
       	Publishable\Repository::query as publishableQuery;
    }

	protected $_alias = 'c';

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

	public function query(array $criteria = array(), $sort = null, $limit = null, $offset = null)
	{
		$query = parent::query($criteria, $sort, $limit, $offset);

		$criteria = $criteria + [
			'search'		=> null,
			'createDateMin'	=> null,
			'createDateMax'	=> null,
			'modifyDateMin'	=> null,
			'modifyDateMax'	=> null,
			'createUsers'	=> [],
			'statuses'		=> [],
			'isPublished'	=> false,
		];
		
		$query->andWhere("c.next IS NULL");
		
		if (isset($sort)) {
			$query->addOrderBy("c.{$sort[0]}", "{$sort[1]}");
		} else {
			$query->addOrderBy("c.modifyDate", "DESC");
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
			$query ->andWhere("c.id IN (:ids)")
				->addSelect("FIELD(c.id, :ids) AS HIDDEN sort")
				->orderBy("sort");
			$query->setParameter('ids', $ids);
		}
		if (isset($criteria['createDateMin'])) {
			$query->andWhere("c.createDate >= :createDateMin");
			$query->setParameter('createDateMin', new \DateTime("{$criteria['createDateMin']}"));
		}
		if (isset($criteria['createDateMax'])) {
			$query->andWhere("c.createDate <= :createDateMax");
			$query->setParameter('createDateMax', new \DateTime("{$criteria['createDateMax']}"));
		}
		if (isset($criteria['modifyDateMin'])) {
			$query->andWhere("c.modifyDate >= :modifyDateMin");
			$query->setParameter('modifyDateMin', new \DateTime("{$criteria['modifyDateMin']}"));
		}
		if (isset($criteria['modifyDateMax'])) {
			$query->andWhere("c.modifyDate <= :modifyDateMax");
			$query->setParameter('modifyDateMax', new \DateTime("{$criteria['modifyDateMax']}"));
		}
		if (count($criteria['createUsers'])) {
			$query->andWhere("c.createUser IN (:createUsers)");
			$query->setParameter('createUsers', $criteria['createUsers']);
		}
		if (count($criteria['statuses'])) {
			$query->andWhere("c.status IN (:statuses)");
			$query->setParameter('statuses', $criteria['statuses']);
		}
		if ($criteria['isPublished']) {
			$query->andWhere("c.status IN ('published') AND c.publishDate >= UTC_DATETIME()");
		}

		$this->publishableQuery($query, $criteria);

		return $query;
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