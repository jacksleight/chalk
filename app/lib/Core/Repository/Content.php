<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Repository;

use Chalk\Chalk,
	Chalk\Repository,
	Chalk\Behaviour\Publishable,
	Chalk\Behaviour\Searchable;

class Content extends Repository
{
	use Publishable\Repository, 
		Searchable\Repository;

	protected $_alias = 'c';

	public function query(array $criteria = array(), $sort = null, $limit = null, $offset = null)
	{
		$query = parent::query($criteria, $sort ?: ['modifyDate', 'DESC'], $limit, $offset);

		$criteria = $criteria + [
			'master'		=> null,
			'createDateMin'	=> null,
			'createDateMax'	=> null,
			'modifyDateMin'	=> null,
			'modifyDateMax'	=> null,
			'createUsers'	=> null,
			'statuses'		=> null,
		];
				
		if (isset($criteria['master'])) {
        	$query
				->andWhere("c.master = :master")
				->setParameter('master', $criteria['master']);
		}
		if (isset($criteria['createDateMin'])) {
			$query
				->andWhere("c.createDate >= :createDateMin")
				->setParameter('createDateMin', new \DateTime("{$criteria['createDateMin']}"));
		}
		if (isset($criteria['createDateMax'])) {
			$query
				->andWhere("c.createDate <= :createDateMax")
				->setParameter('createDateMax', new \DateTime("{$criteria['createDateMax']}"));
		}
		if (isset($criteria['modifyDateMin'])) {
			$query
				->andWhere("c.modifyDate >= :modifyDateMin")
				->setParameter('modifyDateMin', new \DateTime("{$criteria['modifyDateMin']}"));
		}
		if (isset($criteria['modifyDateMax'])) {
			$query
				->andWhere("c.modifyDate <= :modifyDateMax")
				->setParameter('modifyDateMax', new \DateTime("{$criteria['modifyDateMax']}"));
		}
		if (isset($criteria['createUsers'])) {
			$query
				->andWhere("c.createUser IN (:createUsers)")
				->setParameter('createUsers', $criteria['createUsers']);
		}
		if (isset($criteria['statuses'])) {
			$query
				->andWhere("c.status IN (:statuses)")
				->setParameter('statuses', $criteria['statuses']);
		}

		$this->publishableQueryModifier($query, $criteria);
		$this->searchableQueryModifier($query, $criteria);

		return $query;
	}
}