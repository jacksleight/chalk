<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

use Ayre,
	Coast\Model,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
*/
class Tree extends Model
{
	const STATUS_DRAFT		= 'draft';
	const STATUS_PENDING	= 'pending';
	const STATUS_PUBLISHED	= 'published';
	const STATUS_ARCHIVED	= 'archived';

	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

   	/**
     * @ORM\Column(type="integer")
     */
	protected $version = 1;

	/**
     * @ORM\Column(type="string")
     */
	protected $status = self::STATUS_PENDING;
	
    /**
     * @ORM\Column(type="integer")
     */
	protected $root;
	
	protected $_rootNode;

    /**
     * @ORM\Column(type="string")
     */
	protected $name;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createDate;

    /**
     * @ORM\ManyToOne(targetEntity="Ayre\User")
     * @Gedmo\Blameable(on="create")
     */
    protected $createUser;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $modifyDate;

    /**
     * @ORM\ManyToOne(targetEntity="Ayre\User")
     * @Gedmo\Blameable(on="update")
     */
    protected $modifyUser;

	/**
     * @ORM\OneToMany(targetEntity="Ayre\Tree\Node", mappedBy="tree")
     */
	protected $nodes;

	/**
     * @ORM\OneToMany(targetEntity="Ayre\Domain", mappedBy="tree")
     */
	protected $domains;

	/**
     * @ORM\OneToMany(targetEntity="Ayre\Menu", mappedBy="tree")
     */
	protected $menus;

	/**
     * @ORM\ManyToOne(targetEntity="Ayre\Tree", inversedBy="children")
     */
	protected $parent;

	/**
     * @ORM\OneToMany(targetEntity="Ayre\Tree", mappedBy="parent")
     */
	protected $children;

	/**
     * @ORM\OneToMany(targetEntity="Ayre\Action", mappedBy="item")
     */
	protected $actions;

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