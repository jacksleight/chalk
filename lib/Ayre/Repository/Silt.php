<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Repository;

use Ayre\Repository;

class Silt extends Repository
{
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
}