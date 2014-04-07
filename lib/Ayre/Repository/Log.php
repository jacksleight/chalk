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
	public function fetchAll(Loggable $entity)
	{
		$logs = $this->_em->createQueryBuilder()
			->select("l")
			->from("\Ayre\Entity\Log", "l")
			->andWhere("l.entity_type = :entity_type")
			->andWhere("l.entity_id = :entity_id")
			->getQuery()
			->setParameters([
				'entity_type'	=> Ayre::type($entity)->type,
				'entity_id'		=> $entity->id,
			])			
			->getResult();
		return $logs;
	}
}