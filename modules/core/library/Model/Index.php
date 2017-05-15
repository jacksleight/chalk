<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Model;

use Toast\Wrapper;
use Chalk\Chalk;

class Index extends \Toast\Entity
{
	protected $page  = 1;
	protected $limit = 50;
	protected $sort;
	protected $search;
	protected $remember;

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
					'values'	=> [
						50	=> '50',
						100	=> '100',
						200	=> '200',
					],
				),
				'sort' => array(
					'type'		=> 'string',
					'nullable'	=> true,
					'values'	=> [
						'name' => 'Name',
					]
				),
				'search' => array(
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

	public function remember()
	{
		if (func_num_args() > 0) {
			return $this;
		}
		return implode(',', $this->rememberFields());
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
