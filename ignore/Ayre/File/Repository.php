<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre\File;

class Repository extends \Doctrine\ORM\EntityRepository
{
	public function checkFileNameExists($fileName)
	{
		return $this->_em->createQueryBuilder()
			->select("COUNT(r)")
			->from("Ayre\File\Revision", "r")
			->where("r.fileName = :fileName")
			->getQuery()
			->setParameters(array(
				'fileName' => $fileName,
			))
			->getSingleScalarResult() > 0;
	}
}