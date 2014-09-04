<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

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

	public function id($id, $field = 'id')
	{
		$query = $this->query();
		
		$query->where("{$this->_alias}.{$field} = :id");
		$query->setParameter('id', $id);

		return $query
			->getQuery()
			->getSingleResult();
	}

	public function one(array $criteria = array(), $sort, $offset)
	{
		$query = $this->query($criteria, $sort, null, $offset);
		
		return $query
			->setMaxResults(1)
			->getQuery()
			->getOneOrNullResult();
	}

	public function all(array $criteria = array(), $sort = null, $limit = null, $offset = null)
	{
		$query = $this->query($criteria, $sort, $limit, $offset);
		
		return $query
			->getQuery()
			->getResult();
	}

	// @todo DELETE this, BC
	public function fetchAll(array $criteria = array())
	{
		return $this->all($criteria);
	}

	public function query(array $criteria = array(), $sort = null, $limit = null, $offset = null)
	{
		$query = $this->createQueryBuilder($this->_alias);
		
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