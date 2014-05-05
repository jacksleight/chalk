<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

class Index extends \Toast\Entity
{
	protected $search;
	protected $modifyDateMin;
	protected $statuses = [
		\Ayre::STATUS_PENDING,
		\Ayre::STATUS_PUBLISHED,
	];
	protected $contents = [];

	protected static function _defineMetadata($class)
	{
		return array(
			'fields' => array(
				'search' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'modifyDateMin' => array(
					'type'		=> 'string',
					'nullable'	=> true,
					'values'	=> [
						'today'		=> 'Today',
						'-1 hour'	=> 'Past Hour',
						'-1 day'	=> 'Past Day',
						'-2 day'	=> 'Past 2 Days',
						'-1 week'	=> 'Past Week',
						'-2 week'	=> 'Past 2 Weeks',
						'-1 month'	=> 'Past Month',
						'-3 month'	=> 'Past 3 Months',
						'-6 month'	=> 'Past 6 Months',
						'-1 year'	=> 'Past Year',
					]
				),
				'statuses' => array(
					'type'		=> 'array',
					'nullable'	=> true,
					'values'	=> [
						\Ayre::STATUS_PENDING	=> 'Pending',
						\Ayre::STATUS_PUBLISHED	=> 'Published',
						\Ayre::STATUS_ARCHIVED	=> 'Archived',
					],
				),
			),
			'associations' => [
				'contents' => array(
					'type'		=> 'oneToMany',
					'entity'	=> '\Ayre\Entity\Content',
				),
			]
		);
	}
}
