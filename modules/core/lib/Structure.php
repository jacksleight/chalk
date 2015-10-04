<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Core\Structure\Node,
    Chalk\Core\Behaviour\Trackable,
    Chalk\Core\Behaviour\Versionable,
    Chalk\Core\Behaviour\Publishable,
    Chalk\Core\Behaviour\Loggable,
	Coast\Model,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Structure extends \Toast\Entity implements Loggable, Publishable, Trackable, Versionable
{
	public static $chalkSingular = 'Structure';
	public static $chalkPlural   = 'Structures';
    
    use Publishable\Entity,
    	Trackable\Entity,
    	Versionable\Entity {
        	Versionable\Entity::__construct as __constructVersionable;
    	}

	/**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
	
    /**
     * @Column(type="string")
     */
	protected $name;
	
	/**
     * @OneToMany(targetEntity="\Chalk\Core\Structure\Node", mappedBy="structure", cascade={"persist"})
     */
	protected $nodes;
	
	/**
     * @ManyToMany(targetEntity="\Chalk\Core\Domain", mappedBy="structures")
     */
	protected $domains;
	
	public function __construct()
	{	
		$this->nodes	= new ArrayCollection();
		$this->domains	= new ArrayCollection();
		
		$node = new Node();
		$node->structure = $this;
		$this->nodes->add($node);

		$this->__constructVersionable();
	}

	public function root()
	{
		return $this->nodes->first();
	}

	// public function __clone()
	// {
	// 	throw new \Exception('TODO');
	// }
}