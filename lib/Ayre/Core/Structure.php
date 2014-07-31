<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Core;

use Ayre\Core\Structure\Node,
    Ayre\Behaviour\Trackable,
    Ayre\Behaviour\Versionable,
    Ayre\Behaviour\Publishable,
    Ayre\Behaviour\Loggable,
	Coast\Model,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Structure extends \Toast\Entity implements Loggable, Publishable, Trackable, Versionable
{
    public static $info = [
        'singular'  => 'Structure',
        'plural'    => 'Structures',
    ];
    
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
     * @OneToMany(targetEntity="\Ayre\Core\Structure\Node", mappedBy="structure", cascade={"persist"})
     */
	protected $nodes;
	
	/**
     * @OneToMany(targetEntity="\Ayre\Core\Domain", mappedBy="structure")
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

	public function iterator()
	{
		return new \RecursiveIteratorIterator(
			new \Ayre\Core\Structure\Iterator([$this->root]),
			\RecursiveIteratorIterator::SELF_FIRST);
	}

	public function root()
	{
		foreach ($this->nodes as $node) {
			if (!isset($node->parent)) {
				return $node;
			}
		}
	}

	public function __toString()
	{
		return (string) $this->name;
	}

	// public function __clone()
	// {
	// 	throw new \Exception('TODO');
	// }
}