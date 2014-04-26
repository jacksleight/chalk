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
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="entity_class", type="string")
*/
class Structure extends \Toast\Entity implements Loggable, Publishable, Trackable, Versionable
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
     * @ORM\OneToOne(targetEntity="\Ayre\Entity\Structure\Node", inversedBy="structure", cascade={"persist"})
     */
	protected $root;
	
	public function __construct()
	{	
		$this->nodes	= new ArrayCollection();
		$this->actions	= new ArrayCollection();
		
		$this->root = new Entity\Structure\Node();
		$this->root->structure = $this;

		$this->__constructVersionable();
	}

	public function __clone()
	{
		if (isset($this->root)) {
			$this->root = clone $this->root;
		}
	}
}