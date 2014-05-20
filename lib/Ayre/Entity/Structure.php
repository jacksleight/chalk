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
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
*/
class Structure extends \Toast\Entity implements Loggable, Publishable, Trackable, Versionable
{
    use Publishable\Implementation,
    	Trackable\Implementation,
    	Versionable\Implementation {
        	Versionable\Implementation::__construct as __constructVersionable;
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
     * @Column(type="string")
     */
	protected $slug;

	/**
     * @OneToMany(targetEntity="\Ayre\Entity\Structure\Node", mappedBy="structure", cascade={"persist"})
     */
	protected $nodes;
	
	public function __construct()
	{	
		$this->nodes	= new ArrayCollection();
		$this->actions	= new ArrayCollection();
		
		$node = new Entity\Structure\Node();
		$node->structure = $this;
		$this->nodes->add($node);

		$this->__constructVersionable();
	}

	public function name($name = null)
	{
		if (isset($name)) {
			$this->name = $name;
			$this->slug($this->name);
			return $this;
		}
		return $this->name;
	}

	public function slug($slug = null)
	{
		if (isset($slug)) {
			$this->slug = \Coast\str_simplify(iconv('utf-8', 'ascii//translit//ignore', $slug), '-');
			return $this;
		}
		return $this->slug;
	}

	public function label()
	{
		return $this->name;
	}

	public function iterator()
	{
		return new \RecursiveIteratorIterator(
			new \Ayre\Entity\Structure\Iterator([$this->root]),
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

	public function __clone()
	{
		// throw new \Exception('TODO');
	}
}