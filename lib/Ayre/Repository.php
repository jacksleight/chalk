<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

use Doctrine\ORM\EntityRepository;

class Repository extends EntityRepository
{
	public function create()
	{
		$args = func_get_args();
		$reflection = new \ReflectionClass($this->getClassName());
		return $reflection->newInstanceArgs($args);
	}

	public function fetchOrCreate($id)
	{
		$entity = $this->fetch($id);
		if (!isset($entity)) {
			$entity = $this->create();
		}
		return $entity;
	}

	public function fetch($id)
	{
		if (!isset($id)) {
			return;
		}
		return $this->createQueryBuilder('e')
			->andWhere("e.id = :id")
			->getQuery()
			->setParameters(['id' => $id])
			->getOneOrNullResult();
	}

	public function fetchAll(array $criteria = array())
	{
		return $this->createQueryBuilder('e')
			->getQuery()
			->getResult();
	}
}