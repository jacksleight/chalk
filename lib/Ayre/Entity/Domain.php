<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity;

use Ayre\Entity,
    Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
*/
class Domain extends Structure
{
	protected static function _defineMetadata($class)
	{
		return array(
			'fields' => array(
				'name' => array(
					'validator'	=> new \Toast\Validator\Chain(array(
						new \Toast\Validator\Hostname(),
					)),
				),
			),
		);
	}
}