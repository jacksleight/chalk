<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity;

use Ayre\Entity,
    Ayre\Behaviour\Trackable,
    Ayre\Behaviour\Versionable,
    Ayre\Behaviour\Publishable,
    Ayre\Behaviour\Loggable,
	Coast\Model,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
*/
class Tree extends Entity implements Loggable, Publishable, Trackable, Versionable
{
    use Publishable\Implementation,
    	Trackable\Implementation,
    	Versionable\Implementation {
        	Versionable\Implementation::__construct as __constructVersionable;
    	}

	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
	
    /**
     * @ORM\Column(type="string")
     */
	protected $name;

	/**
     * @ORM\OneToOne(targetEntity="\Ayre\Entity\Tree\Node", inversedBy="tree", cascade={"persist"})
     */
	protected $root;

	/**
     * @ORM\OneToMany(targetEntity="\Ayre\Entity\Domain", mappedBy="tree")
     */
	protected $domains;

	/**
     * @ORM\OneToMany(targetEntity="\Ayre\Entity\Menu", mappedBy="tree")
     */
	protected $menus;
	
	public function __construct()
	{	
		$this->nodes	= new ArrayCollection();
		$this->actions	= new ArrayCollection();
		
		$this->root = new Entity\Tree\Node();
		$this->root->tree = $this;

		$this->__constructVersionable();
	}

	public function __clone()
	{
		$this->root = clone $this->root;
	}
}