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
	protected $filters;
	protected $type;
	protected $subtypes = [];
	protected $createDateMin;
	protected $createDateMax;
	protected $modifyDateMin;
	protected $modifyDateMax;
	protected $publishDateMin;
	protected $publishDateMax;
	protected $statuses = [
		Chalk::STATUS_DRAFT,
		Chalk::STATUS_PUBLISHED,
	];

	protected static function _defineMetadata($class)
	{
		return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
			'fields' => array(
				'filters' => array(
					'type'		=> 'array',
				),
				'type' => array(
					'type'		=> 'string',
				),
				'subtypes' => array(
					'type'		=> 'array',
					'nullable'	=> true,
				),
				'createDateMin' => array(
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
				'createDateMax' => array(
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
				'modifyDateMax' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'publishDateMin' => array(
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
				'publishDateMax' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'statuses' => array(
					'type'		=> 'array',
					'nullable'	=> true,
					'values'	=> [
						Chalk::STATUS_DRAFT   	=> 'Draft',
						Chalk::STATUS_PUBLISHED	=> 'Published',
						Chalk::STATUS_ARCHIVED	=> 'Archived',
					],
				),
			)
		));
	}

	public function remembers(array $remembers = [])
	{
		return parent::remembers(array_merge([
			'subtypes',
			'createDateMin',
			'createDateMax',
			'modifyDateMin',
			'modifyDateMax',
			'publishDateMin',
			'publishDateMax',
			'statuses',
		], $remembers));
	}
}


