<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Repository;

use Chalk\Chalk,
	Chalk\Repository,
	Chalk\Behaviour\Publishable,
	Chalk\Behaviour\Searchable,
	DateTime;

class Content extends Repository
{
	use Publishable\Repository, 
		Searchable\Repository;

	protected $_sort = ['modifyDate', 'DESC'];

	public function query(array $params = array())
	{
		$query = parent::query($params);

		$params = $params + [
			'master'		=> null,
			'createDateMin'	=> null,
			'createDateMax'	=> null,
			'modifyDateMin'	=> null,
			'modifyDateMax'	=> null,
			'createUsers'	=> null,
			'statuses'		=> null,
		];
				
		if (isset($params['master'])) {
        	$query
				->andWhere("c.master = :master")
				->setParameter('master', $params['master']);
		}

		if (isset($params['createDateMin'])) {
			$createDateMin = $params['createDateMin'] instanceof DateTime
				? $params['createDateMin']
				: new DateTime($params['createDateMin']);
			$query
				->andWhere("c.createDate >= :createDateMin")
				->setParameter('createDateMin', $createDateMin);
		}
		if (isset($params['createDateMax'])) {
			$createDateMax = $params['createDateMax'] instanceof DateTime
				? $params['createDateMax']
				: new DateTime($params['createDateMax']);
			$query
				->andWhere("c.createDate <= :createDateMax")
				->setParameter('createDateMax', $createDateMax);
		}

		if (isset($params['modifyDateMin'])) {
			$modifyDateMin = $params['modifyDateMin'] instanceof DateTime
				? $params['modifyDateMin']
				: new DateTime($params['modifyDateMin']);
			$query
				->andWhere("c.modifyDate >= :modifyDateMin")
				->setParameter('modifyDateMin', $modifyDateMin);
		}
		if (isset($params['modifyDateMax'])) {
			$modifyDateMax = $params['modifyDateMax'] instanceof DateTime
				? $params['modifyDateMax']
				: new DateTime($params['modifyDateMax']);
			$query
				->andWhere("c.modifyDate <= :modifyDateMax")
				->setParameter('modifyDateMax', $modifyDateMax);
		}

		if (isset($params['createUsers'])) {
			$query
				->andWhere("c.createUser IN (:createUsers)")
				->setParameter('createUsers', $params['createUsers']);
		}
		if (isset($params['statuses'])) {
			$query
				->andWhere("c.status IN (:statuses)")
				->setParameter('statuses', $params['statuses']);
		}

		$this->publishableQueryModifier($query, $params);
		$this->searchableQueryModifier($query, $params);

		return $query;
	}
}