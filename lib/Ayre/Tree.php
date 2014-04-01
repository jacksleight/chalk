<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

use Ayre,
    Ayre\Behaviour,
	Coast\Model,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
*/
class Tree extends Model implements Behaviour\Trackable, Behaviour\Versionable, Behaviour\Publishable
{
    use Behaviour\Trackable\Implementation;
    use Behaviour\Versionable\Implementation;
    use Behaviour\Publishable\Implementation;

	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
	
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
     * @ORM\ManyToOne(targetEntity="Ayre\Tree", inversedBy="followers")
     */
	protected $master;

	/**
     * @ORM\OneToMany(targetEntity="Ayre\Tree", mappedBy="master")
     */
	protected $followers;

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