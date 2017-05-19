<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Model\Crud;

use Chalk\Chalk;
use	Doctrine\Common\Collections\ArrayCollection;

class Index extends \Toast\Entity
{
	protected $page = 1;
	protected $limit = 50;
	protected $sort;
	protected $search;
	protected $tags;
	protected $tagsList;

	protected $selected;
	protected $selectedList;
	protected $batch;

	protected $remember;

	protected $_entityClass;
	protected $_sorts;
	protected $_limits;
	protected $_batches;

	protected static function _defineMetadata($class)
	{
		return array(
			'fields' => array(
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
				'search' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'tags' => array(
					'type'		=> 'array',
					'nullable'	=> true,
				),
				'tagsList' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'selected' => array(
					'type'		=> 'array',
					'nullable'	=> true,
				),
				'selectedList' => array(
					'type'		=> 'string',
					'nullable'	=> true,
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
		);
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

	public function __construct($entityClass, $sorts = [], $limits = [], $batches = [])
	{
		$this->_entityClass	= $entityClass;
		$this->_sorts		= $sorts;
		$this->_limits		= $limits;
		$this->_batches		= $batches;
	}

	public function tags(array $tags = null)
	{
		if (func_num_args() > 0) {
			if ($tags == 'none') {
				$this->tagsList = $tags;
			} else if (isset($tags)) {
				$this->tagsList = implode('.', $tags);
			} else {
				$this->tagsList = null;
			}
		}
		if ($this->tagsList == 'none') {
			return $this->tagsList;
		} else if (isset($this->tagsList)) {
			return explode('.', $this->tagsList);
		} else {
			return null;
		}
	}

	public function selected(array $selected = null)
	{
		if (func_num_args() > 0) {
			$this->selectedList = implode('.', $selected);
			return $this;
		}
		return isset($this->selectedList)
			? explode('.', $this->selectedList)
			: [];
	}

	public function remember()
	{
		if (func_num_args() > 0) {
			return $this;
		}
		return implode('.', $this->rememberFields());
	}

	public function rememberFields(array $fields = [])
	{
		return array_merge([
			'page',
			'limit',
			'sort',
			'search',
		], $fields);
	}
}
