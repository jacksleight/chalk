<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Doctrine\ORM\EntityRepository,
	Doctrine\ORM\QueryBuilder,
	Doctrine\ORM\Query;

class Repository extends EntityRepository
{
	protected $_alias = 'e';

	public function create()
	{
		$args = func_get_args();
		$reflection = new \ReflectionClass($this->getClassName());
		return $reflection->newInstanceArgs($args);
	}

	public function id($id, array $criteria = array())
	{
		return $this->query(array_merge($criteria, ['ids' => [$id]]))
			->getQuery()
			->getOneOrNullResult();
	}

	public function one(array $criteria = array(), $sort = null, $offset = null)
	{	
		return $this->query($criteria, $sort, null, $offset)
			->setMaxResults(1)
			->getQuery()
			->getOneOrNullResult();
	}

	public function all(array $criteria = array(), $sort = null, $limit = null, $offset = null)
	{	
		return $this->query($criteria, $sort, $limit, $offset)
			->getQuery()
			->getResult();
	}

	public function count(array $criteria = array(), $limit = null, $offset = null)
	{	
		return $this->query($criteria, null, $limit, $offset)
			->select("COUNT({$this->_alias})")
			->getQuery()
			->getSingleScalarResult();
	}

	public function query(array $criteria = array(), $sort = null, $limit = null, $offset = null)
	{
		$query = $this->createQueryBuilder($this->_alias);

		$criteria = $criteria + [
			'ids' => null,
		];
		
		if (isset($criteria['ids'])) {
			$query
				->andWhere("{$this->_alias}.id IN (:ids)")
				->setParameter('ids', $criteria['ids']);
		}

		if (isset($sort)) {
			if (!is_array($sort)) {
				$sort = [$sort, 'ASC'];
			}
			$query->orderBy("{$this->_alias}.{$sort[0]}", $sort[1]);
		}
		if (isset($limit)) {
			$query->setMaxResults($limit);
		}
		if (isset($offset)) {
			$query->setFirstResult($offset);
		}
		
		return $query;
	}
}