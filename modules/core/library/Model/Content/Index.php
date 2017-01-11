<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Model\Content;

use Toast\Wrapper;
use Chalk\Core\Model\Index as CoreIndex;
use Chalk\App as Chalk;

class Index extends CoreIndex
{
	protected $filters;
	protected $type;
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
	protected $contentNew;
	protected $contentList;
	protected $entity;
	protected $batch;

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
				'sort' => array(
					'values'	=> [
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
				'contentNew' => array(
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
		));
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

	public function contentNew($value = null)
	{
		if (func_num_args() > 0) {
			$this->contentNew = $value;
			$this->contentIds(json_encode([$this->contentNew]));
			return $this;
		}
		return $this->contentNew;
	}
}