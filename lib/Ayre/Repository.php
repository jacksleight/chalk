<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

use Doctrine\ORM\EntityRepository;

class Repository extends EntityRepository
{
	public function create()
	{
		$args = func_get_args();
		$reflection = new \ReflectionClass($this->getClassName());
		return $reflection->newInstanceArgs($args);
	}

	public function findOrCreate($id = null)
	{
		return isset($id)
			? $this->find($id)
			: $this->create();
	}

	public function fetchAll()
	{
		return $this->findAll();
	}
}