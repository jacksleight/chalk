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

	protected $entities;
	protected $entityIds;
	protected $entityNew;
	protected $batch;

	protected $remember;

	protected $_entityClass;

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
					],
				),
				'search' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'entityIds' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'entityNew' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'remember' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'batch' => array(
					'type'		=> 'string',
					'nullable'	=> true,
					'values'	=> [
						'delete'	=> 'Delete',
					],
				),
			),
			'associations' => [
				'entities' => array(
					'type' => 'oneToMany',
				),
			]
		);
	}

	public function __construct($entityClass)
	{
		$this->_entityClass = $entityClass;
        $this->entities = new ArrayCollection();
	}

	public function entityIds($value = null)
	{
		if (func_num_args() > 0) {
			$this->entities->clear();
			$ids = json_decode($value, true);
			foreach ($ids as $id) {
				$entity = \Toast\Wrapper::$em->getReference($this->_entityClass, $id);
				if ($entity) {
					$this->entities->add($entity);
				}
			}
			return $this;
		}
		$ids = [];
		foreach ($this->entities as $entity) {
			$ids[] = $entity->id;
		}
		return json_encode($ids);
	}

	public function entityNew($value = null)
	{
		if (func_num_args() > 0) {
			$this->entityNew = $value;
			$this->entityIds(json_encode([$this->entityNew]));
			return $this;
		}
		return $this->entityNew;
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
