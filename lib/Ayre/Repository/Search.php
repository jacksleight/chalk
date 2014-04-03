<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Repository;

use Ayre\Entity,
	Ayre\Behaviour\Searchable,
	Ayre\Repository;

class Search extends Repository
{
	public function fetch(Searchable $entity)
	{
		$class = get_class($entity);
		if (isset($entity->id)) {
			$search = $this->_em->createQueryBuilder()
				->select("s")
				->from("\Ayre\Entity\Search", "s")
				->andWhere("s.class = :class")
				->andWhere("s.class_id = :class_id")
				->getQuery()
				->setParameters([
					'class'		=> $class,
					'class_id'	=> $entity->id,
				])			
				->getOneOrNullResult();
		} else {
			$search				= new Entity\Search();
			$search->class		= $class;
			$search->class_obj	= $entity;
		}
		return $search;
	}

	public function search($phrase, $class = null)
	{
		$conn	= $this->_em->getConnection();
		$phrase	= $conn->quote($phrase);
		$where	= isset($class)	
			? "AND s.class = {$conn->quote($class)}"
			: null;
		return $conn->query("
			SELECT s.class, s.class_id,
				MATCH(s.content) AGAINST ({$phrase} IN BOOLEAN MODE) AS score
			FROM search AS s
			WHERE MATCH(s.content) AGAINST ({$phrase} IN BOOLEAN MODE)
				{$where}
			ORDER BY score DESC
		")->fetchAll();
	}
}