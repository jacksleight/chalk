<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre\Search;

use Ayre;

class Repository extends \Doctrine\ORM\EntityRepository
{
	public function fetch(\Ayre\Behaviour\Searchable $entity)
	{
		$class = get_class($entity);
		if (isset($entity->id)) {
			$search = $this->_em->createQueryBuilder()
				->select("s")
				->from("Ayre\Search", "s")
				->andWhere("s.class = :class")
				->andWhere("s.class_id = :class_id")
				->getQuery()
				->setParameters([
					'class'		=> $class,
					'class_id'	=> $entity->id,
				])			
				->getOneOrNullResult();
		} else {
			$search				= new Ayre\Search();
			$search->class		= $class;
			$search->class_obj	= $entity;
		}
		return $search;
	}
}