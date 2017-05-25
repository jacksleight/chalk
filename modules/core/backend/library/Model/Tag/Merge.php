<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Model\Tag;

use Chalk\Core\Backend\Model;
use Coast\Validator;

class Merge extends Model
{
	protected $sourceTag;

	protected $targetTag;

	protected static function _defineMetadata($class)
	{
        return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
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
		));
	}
}