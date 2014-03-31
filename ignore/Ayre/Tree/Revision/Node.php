<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre\Tree\Revision;

class Node extends \Js\Entity\Doctrine implements \DoctrineExtensions\NestedSet\MultipleRootNode
{
	protected $id;
	protected $root;
	protected $lft;
	protected $rgt;

	protected $revision;
	protected $item;

	protected static function _defineMetadata($class)
	{
		return array(
			'table' => 'tree_revision_node',
			'fields' => array(
				'id' => array(
					'id'		=> true,
					'auto'		=> true,
					'type'		=> 'integer',
				),
				'root' => array(
					'type'		=> 'integer',
				),
				'lft' => array(
					'type'		=> 'integer',
				),
				'rgt' => array(
					'type'		=> 'integer',
				),
			),
			'associations' => array(
				'revision' => array(
					'type'		=> 'manyToOne',
					'entity'	=> 'Ayre\Tree\Revision',
					'inverse'	=> 'nodes',
					'onDelete'	=> 'CASCADE',
				),
				'item' => array(
					'type'		=> 'manyToOne',
					'entity'	=> 'Ayre\Item',
					'inverse'	=> 'nodes',
					'nullable'	=> true,
				),
			),
		);
	}

	public static function getNsm()
	{
		return new \DoctrineExtensions\NestedSet\Manager(
			new \DoctrineExtensions\NestedSet\Config(
				\Ayre::$instance->em, get_called_class()));
	}

	public function getNode()
	{
		return self::getNsm()->wrapNode($this);
	}

	public function createPath()
	{
		$path = new \Ayre\Tree\Revision\Node\Path();
		$path->node = $this;
		$this->paths[] = $path;
		return $path;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getRootValue()
	{
		return $this->root;
	}

	public function setRootValue($root)
	{
		$this->root = $root;
	}

	public function getLeftValue()
	{
		return $this->lft;
	}

	public function setLeftValue($lft)
	{
		$this->lft = $lft;
	}

	public function getRightValue()
	{
		return $this->rgt;
	}

	public function setRightValue($rgt)
	{
		$this->rgt = $rgt;
	}
}