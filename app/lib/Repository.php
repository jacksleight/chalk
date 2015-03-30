<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
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

	public function slug($slug, array $criteria = array())
	{
		return $this->query(array_merge($criteria, ['slugs' => [$slug]]))
			->getQuery()
			->getOneOrNullResult();
	}

	public function one(array $criteria = array(), $sort = null, $offset = null)
	{	
		return $this->query($criteria, $sort, null, $offset)
			->getQuery()
			->getOneOrNullResult();
	}

	public function all(array $criteria = array(), $sort = null, $limit = null, $offset = null)
	{	
		return $this->query($criteria, $sort, $limit, $offset)
			->getQuery()
			->getResult();
	}

	public function paged(array $criteria = array(), $sort = null, $limit = null, $page = null)
	{	
		$query = $this->query($criteria, $sort, $limit, $limit * ($page - 1));
		
		return new \Doctrine\ORM\Tools\Pagination\Paginator($query);
	}

	public function count(array $criteria = array())
	{	
		return $this->query($criteria)
			->select("COUNT({$this->_alias})")
			->getQuery()
			->getSingleScalarResult();
	}

	public function query(array $criteria = array(), $sort = null, $limit = null, $offset = null)
	{
		$query = $this->createQueryBuilder($this->_alias);

		$criteria = $criteria + [
			'ids'	=> null,
			'slugs'	=> null,
		];
		
		if (isset($criteria['ids'])) {
			$query
				->andWhere("{$this->_alias}.id IN (:ids)")
				->setParameter('ids', $criteria['ids']);
		} else if (isset($criteria['slugs'])) {
			$query
				->andWhere("{$this->_alias}.slug IN (:slugs)")
				->setParameter('slugs', $criteria['slugs']);
		}

		if (isset($sort)) {
			if ($sort == 'random') {
				$query->addSelect('RAND() AS HIDDEN rand')->orderBy('rand');
			} else {
				if (!is_array($sort)) {
					$sort = [$sort, 'ASC'];
				}
				$query->orderBy("{$this->_alias}.{$sort[0]}", $sort[1]);
			}
		} else if (isset($criteria['ids'])) {
			$query
				->addSelect("FIELD({$this->_alias}.id, :ids) AS HIDDEN sort")
				->orderBy("sort");
		} else if (isset($criteria['slugs'])) {
			$query
				->addSelect("FIELD({$this->_alias}.slug, :slugs) AS HIDDEN sort")
				->orderBy("sort");
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