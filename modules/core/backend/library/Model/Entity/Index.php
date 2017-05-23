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
	protected $page = 1;
	protected $limit = 50;
	protected $sort;
	protected $layout = 'table';
	protected $search;

	protected $batch;

	protected $_sorts;
	protected $_limits;
	protected $_batches;

	protected static function _defineMetadata($class)
	{
		return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
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
				'layout' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'search' => array(
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
		));
	}

	public function data($sorts = [], $limits = [], $batches = [])
	{
		$this->_sorts		= $sorts;
		$this->_limits		= $limits;
		$this->_batches		= $batches;
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
		], $remembers);
	}
}
