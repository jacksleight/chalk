<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre\Silt;

class Repository extends \Doctrine\ORM\EntityRepository
{
	// public function fetch($silt)
	// {
	// 	return $this->_createQueryBuilder()
	// 		->andWhere("i = :silt")
	// 		->setParameters(array(
	// 			'silt' => $silt,
	// 		))
	// 		->getQuery()
	// 		->getSingleResult();
	// }

	// public function fetchAll()
	// {
	// 	return $this->_createQueryBuilder()
	// 		->getQuery()
	// 		->getResult();
	// }

	public function fetchAllForPublish()
	{
		return $this->_em->createQueryBuilder()
			->select("silt")
			->from("Ayre\Silt", "silt")
			->where("silt.status IN (:statuses)")
			->addOrderBy("silt.master")
			->addOrderBy("silt.version", "DESC")
			->getQuery()
			->setParameters([
				'statuses' => [
					\Ayre::STATUS_PENDING,
					\Ayre::STATUS_PUBLISHED,
				],
			])
			->getResult();
	}

	// protected function _createQueryBuilder()
	// {
	// 	return $this->_em->createQueryBuilder()
	// 		->select("i")
	// 		->from("Ayre\Silt", "i")
	// 		->addOrderBy("silt.master")
	// 		->addOrderBy("silt.version");
	// }
	// 
	// 
	// public static function search($phrase)
	// {
	// 	$conn = \Ayre::$instance->em->getConnection();
		
	// 	$phrase = $conn->quote($phrase);
	// 	return \JS\array_column($conn->query("
	// 		SELECT s.id,
	// 			MATCH(s.content) AGAINST ({$phrase} IN BOOLEAN MODE) AS score
	// 		FROM search AS s
	// 		WHERE MATCH(s.content) AGAINST ({$phrase} IN BOOLEAN MODE)
	// 		ORDER BY score DESC
	// 	")->fetchAll(), 'id');
	// }
}