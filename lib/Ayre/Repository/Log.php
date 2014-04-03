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
			->andWhere("l.class = :class")
			->andWhere("l.class_id = :class_id")
			->getQuery()
			->setParameters([
				'class'		=> Ayre::resolve($entity)->short,
				'class_id'	=> $entity->id,
			])			
			->getResult();
		return $logs;
	}
}