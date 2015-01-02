<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Repository;

use Chalk\Chalk,
	Chalk\Repository,
	Chalk\Behaviour\Publishable;

class Content extends Repository
{
	use Publishable\Repository {
       	Publishable\Repository::queryModifier as publishableQueryModifier;
    }

	protected $_alias = 'c';

	public function query(array $criteria = array(), $sort = null, $limit = null, $offset = null)
	{
		$query = parent::query($criteria, $sort, $limit, $offset);

		$criteria = $criteria + [
			'search'		=> null,
			'master'		=> null,
			'createDateMin'	=> null,
			'createDateMax'	=> null,
			'modifyDateMin'	=> null,
			'modifyDateMax'	=> null,
			'createUsers'	=> null,
			'statuses'		=> null,
		];
				
		if (isset($criteria['search'])) {
			if ($this->_class->name == 'Chalk\Core\Content') {
				$classes = $this->_em->getClassMetadata($this->_class->name)->subClasses;
			} else {
				$classes = [$this->_class->name];
			}
			$results = $this->_em->getRepository('Chalk\Core\Index')
				->search($criteria['search'], $classes);
			$ids = \Coast\array_column($results, 'entityId');
			$query
				->addSelect("FIELD(c.id, :ids) AS HIDDEN sort")
				->andWhere("c.id IN (:ids)")
				->orderBy("sort")
				->setParameter('ids', $ids);
		}
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

		if (!isset($sort)) {
			$query
				->orderBy("c.modifyDate", "DESC")
				->addOrderBy("c.id", "DESC");
		}

		$this->publishableQueryModifier($query, $criteria);

		return $query;
	}
}