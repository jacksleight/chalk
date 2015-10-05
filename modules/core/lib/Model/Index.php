<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Model;

use Toast\Wrapper;
use Chalk\App as Chalk;

class Index extends \Toast\Entity
{
	protected $page  = 1;
	protected $limit = 50;
	protected $filters;
	protected $type;
	protected $sort = 'modifyDate,DESC';
	protected $search;
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
	protected $subtypes = [];
	protected $contents = [];
	protected $contentIds;
	protected $entity;
	protected $batch;

	protected static function _defineMetadata($class)
	{
		return array(
			'fields' => array(
				'limit' => array(
					'type'		=> 'integer',
					'nullable'	=> true,
					'values'	=> [
						50	=> '50',
						100	=> '100',
						200	=> '200',
					],
				),
				'filters' => array(
					'type'		=> 'array',
				),
				'type' => array(
					'type'		=> 'string',
				),
				'page' => array(
					'type'		=> 'integer',
				),
				'sort' => array(
					'type'		=> 'string',
					'nullable'	=> true,
					'values'	=> [
						'name'				=> 'Name',
						'modifyDate,DESC'	=> 'Updated',
						'publishDate'		=> 'Published',
						'status'			=> 'Status',
					]
				),
				'subtypes' => array(
					'type'		=> 'array',
					'nullable'	=> true,
				),
				'contentIds' => array(
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
				'entity' => array(
					'type'		=> 'text',
					'nullable'	=> true,
				),
				'batch' => array(
					'type'		=> 'string',
					'nullable'	=> true,
					'values'	=> [
						'publish'	=> 'Publish',
						'archive'	=> 'Archive',
						'restore'	=> 'Restore',
						'delete'	=> 'Delete',
					],
				),
			),
			'associations' => [
				'contents' => array(
					'type'		=> 'oneToMany',
					'entity'	=> '\Chalk\Core\Content',
				),
			]
		);
	}

	public function contentIds($value = null)
	{
		if (func_num_args() > 0) {
			$this->contents->clear();
			$ids = json_decode($value, true);
			foreach ($ids as $id) {
				$content = Wrapper::$em->getReference('Chalk\Core\Content', $id);
				if ($content) {
					$this->contents->add($content);
				}
			}
			return $this;
		}
		$ids = [];
		foreach ($this->contents as $content) {
			$ids[] = $content->id;
		}
		return json_encode($ids);
	}
}
