<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre\Item;

abstract class Revision extends \Js\Entity\Doctrine implements \Ayre\Action\Logged
{
	const STATUS_DRAFT		= 'draft';
	const STATUS_PENDING	= 'pending';
	const STATUS_PUBLISHED	= 'published';
	const STATUS_ARCHIVED	= 'archived';
	
	protected $item;
	protected $actions;
	protected $search;

	protected $id;
	protected $status = self::STATUS_PENDING;
	protected $activeStartDate;
	protected $activeEndDate;
	protected $revisionDate;
	protected $name;
	protected $label;
	protected $slug;

	protected static function _defineMetadata($class)
	{
		return array(
			'table' => 'item_revision',
			'inheritance' => array(
				'type'		=> 'singleTable',
				'column'	=> array('_discriminator', 'string', 25),
				'map'		=> \Ayre::$instance->app->getItemTypeMap('Revision'),
			),
			'fields' => array(
				'id' => array(
					'id'		=> true,
					'auto'		=> true,
					'type'		=> 'integer',
				),
				'status' => array(
					'type'		=> 'string',
					'values'	=> array(
						self::STATUS_DRAFT,
						self::STATUS_PENDING,
						self::STATUS_PUBLISHED,
						self::STATUS_ARCHIVED,
					),
				),
				'activeStartDate' => array(
					'type'		=> 'datetime',
					'nullable'	=> true,
				),
				'activeEndDate' => array(
					'type'		=> 'datetime',
					'nullable'	=> true,
				),
				'revisionDate' => array(
					'type'		=> 'datetime',
				),
				'name' => array(
					'type'		=> 'string',
				),
				'label' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'slug' => array(
					'type'		=> 'string',
					'nullable'	=> true,
					'validator'	=> new \Js\Validator\Chain(array(
						new \Js\Validator\Regex('/^[a-z0-9\-_]+$/'),
					)),			
				),
			),
			'associations' => array(
				'item' => array(
					'type'		=> 'manyToOne',
					'entity'	=> substr($class, 0, strrpos($class, '\\')),
					'inverse'	=> 'revisions',
				),
				'actions' => array(
					'type'		=> 'oneToMany',
					'entity'	=> "Ayre\Action",
					'inverse'	=> 'revision',
				),
				'search' => array(
					'type'		=> 'oneToOne',
					'entity'	=> "Ayre\Search",
					'inverse'	=> 'revision',
					'cascade'	=> array('remove'),
				),
			),
		);
	}

	public function __construct()
	{
		parent::__construct();

		$this->revisionDate =  new \DateTime();
		
		$search = new \Ayre\Search();
		$search->version = $this;
		$this->search = $search;
	}
	
	public function getSmartLabel()
	{
		return isset($this->label)
			? $this->label
			: $this->name;
	}
	
	public function getSmartSlug()
	{
		if (isset($this->slug)) {
			return $this->slug;
		}
		$slug = \JS\str_simplify(iconv('utf-8', 'ascii//translit//ignore', $this->smartLabel), '-');
		return strlen($slug)
			? strtolower($slug)
			: null;
	}
	
	public function getSearchContent()
	{
		return \JS\array_filter_null(array(
			$this->name,
			$this->label,
		));
	}
}