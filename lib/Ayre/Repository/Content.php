<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Repository;

use Ayre\Repository;

class Content extends Repository
{
	public function fetchAllForPublish()
	{
		return $this->_em->createQueryBuilder()
			->select("content")
			->from("Ayre\Content", "content")
			->where("content.status IN (:statuses)")
			->addOrderBy("content.master")
			->addOrderBy("content.version", "DESC")
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