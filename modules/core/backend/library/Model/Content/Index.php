<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md.
 */

namespace Chalk\Core\Backend\Model\Content;

use Chalk\Chalk;
use Chalk\Core\Backend\Model\Entity\Index as EntityIndex;

class Index extends EntityIndex
{
	protected $type;
	protected $subtypes = [];

	protected static function _defineMetadata($class)
	{
		return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
			'fields' => array(
				'type' => array(
					'type'		=> 'string',
				),
				'subtypes' => array(
					'type'		=> 'array',
					'nullable'	=> true,
				),
			)
		));
	}

	public function remembers(array $remembers = [])
	{
		return parent::remembers(array_merge([
			'subtypes',
		], $remembers));
	}
}


