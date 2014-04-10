<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

class Filter extends \Ayre\Entity
{
	protected $search;
	protected $createDateMin;
	protected $createUsers = [];
	protected $statuses = [
		\Ayre::STATUS_DRAFT,
		\Ayre::STATUS_PENDING,
		\Ayre::STATUS_PUBLISHED,
	];

	protected static function _defineMetadata($class)
	{
		return array(
			'fields' => array(
				'search' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'createDateMin' => array(
					'type'		=> 'string',
					'nullable'	=> true,
					'values'	=> [
						'today'		=> 'Today',
						'yesterday'	=> 'Since Yesterday',
						'-1 week'	=> 'Last 1 Week',
						'-2 weeks'	=> 'Last 2 Weeks',
						'-1 month'	=> 'Last 4 Month',
						'-3 month'	=> 'Last 3 Months',
						'-6 month'	=> 'Last 6 Months',
						'-1 year'	=> 'Last 1 Year',
					]
				),
				'createUsers' => array(
					'type'		=> 'array',
					'nullable'	=> true,
				),
				'statuses' => array(
					'type'		=> 'array',
					'nullable'	=> true,
					'values'	=> [
						\Ayre::STATUS_DRAFT		=> 'Draft',
						\Ayre::STATUS_PENDING	=> 'Pending',
						\Ayre::STATUS_PUBLISHED	=> 'Published',
						\Ayre::STATUS_ARCHIVED	=> 'Archived',
					],
				),
			),
		);
	}
}
