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
	// @todo this isn't fetch all!
	public function fetchAll(Loggable $entity = null)
	{
		$logs = $this->_em->createQueryBuilder()
			->select("l")
			->from("\Ayre\Entity\Log", "l")
			->andWhere("l.entity_class = :entity_class")
			->andWhere("l.entity_id = :entity_id")
			->getQuery()
			->setParameters([
				'entity_class'	=> get_class($entity),
				'entity_id'		=> $entity->id,
			])			
			->getResult();
		return $logs;
	}
}