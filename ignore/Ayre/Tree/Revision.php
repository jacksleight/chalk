<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre\Tree;

class Revision extends \Js\Entity\Doctrine implements \Ayre\Action\Logged
{
	const STATUS_DRAFT		= 'draft';
	const STATUS_PENDING	= 'pending';
	const STATUS_PUBLISHED	= 'published';
	const STATUS_ARCHIVED	= 'archived';

	protected $tree;
	protected $nodes;
	protected $actions;

	protected $id;
	protected $status = self::STATUS_PENDING;
	protected $root;

	protected $_rootNode;

	protected static function _defineMetadata($class)
	{
		return array(
			'table' => 'tree_revision',
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
				'root' => array(
					'type'		=> 'integer',
					'nullable'	=> true,
				),
			),
			'associations' => array(
				'tree' => array(
					'type'		=> 'manyToOne',
					'entity'	=> 'Ayre\Tree',
					'inverse'	=> 'revisions',
					'onDelete'	=> 'CASCADE',
				),
				'nodes' => array(
					'type'		=> 'oneToMany',
					'entity'	=> "Ayre\Tree\Revision\Node",
					'inverse'	=> 'revision',
				),
				'actions' => array(
					'type'		=> 'oneToMany',
					'entity'	=> "Ayre\Action",
					'inverse'	=> 'revision',
				),
			),
		);
	}

	public function createNestedSet(\Ayre\Tree\Revision $sourceRevision = null)
	{
		if (isset($this->root)) {
			throw new \Exception("Nested set has already been created");
		}

		$nsm				= \Ayre\Tree\Revision\Node::getNsm();
		$this->_rootNode	= $nsm->createRoot($this->createNode());
		$this->root			= $this->_rootNode->getNode()->id;
		
		if (isset($sourceRevision)) {
			$map		= array();
			$sourceNode	= $sourceRevision->getRootNode();
			$map[$sourceNode->getNode()->id] = $this->_rootNode->getNode()->id;
			$that = $this;
			$iterate = function(\DoctrineExtensions\NestedSet\NodeWrapper $sourceNode, \DoctrineExtensions\NestedSet\NodeWrapper $targetNode) use (&$iterate, &$that, &$map) {
				foreach ($sourceNode->getChildren() as $childNode) {
					$node		= $that->createNode();
					$node->item	= $childNode->getNode()->item;
					foreach ($childNode->getNode()->paths as $childPath) {
						$path = clone $childPath;
						$path->node = $node;
						$node->paths->add($path);
					}
					$targetNode->addChild($node);
					$map[$childNode->getNode()->id] = $node->id;
					$iterate($childNode, $node->getNode());
				}
			};
			$iterate($sourceNode, $this->_rootNode);
			return $map;
		}
	}

	public function getRootNode()
	{
		if (!isset($this->_rootNode)) {
			$nsm = \Ayre\Tree\Revision\Node::getNsm();
			$this->_rootNode = $nsm->fetchTree($this->root);
		}
		return $this->_rootNode;
	}

	public function createNode()
	{
		$node = new \Ayre\Tree\Revision\Node();
		$node->revision = $this;
		$this->nodes[] = $node;
		return $node;
	}

	public function __clone()
	{
		$this->root = null;		
		if (isset($this->nodes)) {
			foreach ($this->nodes as $node) {
				$this->nodes->removeElement($node);
			}
		}
	}
}