<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Model\Tag;

use Coast\Validator;

class Merge extends \Toast\Entity
{
	protected $sourceTag;

	protected $targetTag;

	protected static function _defineMetadata($class)
	{
		return array(
			'associations' => array(
				'sourceTag' => array(
					'type'		=> 'manyToOne',
					'entity'	=> 'Chalk\Core\Tag',
				),
				'targetTag' => array(
					'type'		=> 'manyToOne',
					'entity'	=> 'Chalk\Core\Tag',
				),
			),
		);
	}
}