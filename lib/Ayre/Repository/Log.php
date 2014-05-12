<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Repository;

use Ayre,
	Ayre\Entity,
	Ayre\Behaviour\Loggable,
	Ayre\Repository;

class Log extends Repository
{
	public function fetchAll(array $criteria = array())
	{
		$params = [];
		$qb = $this->_em->createQueryBuilder()
			->select("l")
			->from("\Ayre\Entity\Log", "l");
		if (isset($critera['entity'])) {
			$qb ->andWhere("l.entity_class = :entity_class")
				->andWhere("l.entity_id = :entity_id");
			$params['entity_class']	= get_class($entity);
			$params['entity_id']	= $entity->id;
		}
		return $qb
			->getQuery()
			->setParameters($params)		
			->getResult();
	}
}