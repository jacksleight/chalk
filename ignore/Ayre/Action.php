<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre;

class Action extends \Js\Entity\Doctrine
{
	const TYPE_CREATE			= 'create';
	const TYPE_UPDATE			= 'update';
	const TYPE_STATUS_PENDING	= 'status_pending';
	const TYPE_STATUS_PUBLISHED	= 'status_published';
	const TYPE_STATUS_ARCHIVED	= 'status_archived';

	protected $id;
	protected $actionDate;
	protected $type;

	protected $itemRevision;
	protected $treeRevision;
	protected $user;

	protected static function _defineMetadata($class)
	{
		return array(
			'table' => 'action',
			'fields' => array(
				'id' => array(
					'id'		=> true,
					'auto'		=> true,
					'type'		=> 'integer',
				),
				'actionDate' => array(
					'type'		=> 'datetime',
				),
				'type' => array(
					'type'		=> 'string',
					'values' => array(
						self::TYPE_CREATE,
						self::TYPE_UPDATE,
						self::TYPE_STATUS_PENDING,
						self::TYPE_STATUS_PUBLISHED,
						self::TYPE_STATUS_ARCHIVED,
					),
				),
			),
			'associations' => array(
				'itemRevision' => array(
					'type'		=> 'manyToOne',
					'entity'	=> 'Ayre\Item\Revision',
					'inverse'	=> 'actions',
					'nullable'	=> true,
					'onDelete'	=> 'CASCADE',
				),
				'treeRevision' => array(
					'type'		=> 'manyToOne',
					'entity'	=> 'Ayre\Tree\Revision',
					'inverse'	=> 'actions',
					'nullable'	=> true,
					'onDelete'	=> 'CASCADE',
				),
				'user' => array(
					'type'		=> 'manyToOne',
					'entity'	=> 'Ayre\User',
					'inverse'	=> 'actions',
					'nullable'	=> true,
					'onDelete'	=> 'SET NULL',
				),
			),
		);
	}

	public function prePersist()
	{
		parent::prePersist();

		$this->actionDate = new \DateTime();
	}
}