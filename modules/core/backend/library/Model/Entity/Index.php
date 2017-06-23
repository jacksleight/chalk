<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Model\Entity;

use Chalk\Chalk;
use Chalk\Core\Backend\Model;
use	Doctrine\Common\Collections\ArrayCollection;

class Index extends Model
{
	protected $filters;
	protected $page = 1;
	protected $limit = 50;
	protected $sort;
	protected $layout = 'table';
	protected $search;

	protected $createDateMin;
	protected $createDateMax;
	protected $updateDateMin;
	protected $updateDateMax;
	protected $publishDateMin;
	protected $publishDateMax;
	protected $statuses = [
		Chalk::STATUS_DRAFT,
		Chalk::STATUS_PUBLISHED,
	];

	protected $batch;

	protected $_sorts;
	protected $_limits;
	protected $_batches;

	protected static function _defineMetadata($class)
	{
		return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
			'fields' => array(
				'filters' => array(
					'type'		=> 'array',
				),
				'page' => array(
					'type'		=> 'integer',
				),
				'limit' => array(
					'type'		=> 'integer',
					'nullable'	=> true,
				),
				'sort' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'layout' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'search' => array(
					'type'		=> 'string',
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
				'updateDateMin' => array(
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
				'updateDateMax' => array(
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
				'batch' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'remember' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
			),
		));
	}

	public function options($sorts = [], $limits = [], $batches = [])
	{
		$this->_sorts	= $sorts;
		$this->_limits	= $limits;
		$this->_batches	= $batches;
	}

	protected function _alterMetadata($name, $value)
	{
		if ($name == 'sort') {
			$value['values'] = $this->_sorts;
		} else if ($name == 'limit') {
			$value['values'] = $this->_limits;
		} else if ($name == 'batch') {
			$value['values'] = $this->_batches;
		}
		return $value;
	}

	public function remembers(array $remembers = [])
	{
		return array_merge([
			'page',
			'limit',
			'sort',
			'search',
			'createDateMin',
			'createDateMax',
			'updateDateMin',
			'updateDateMax',
			'publishDateMin',
			'publishDateMax',
			'statuses',
		], $remembers);
	}
}
