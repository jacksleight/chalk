<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Repository;

use Ayre\Repository;

class Silt extends Repository
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
	// 
}