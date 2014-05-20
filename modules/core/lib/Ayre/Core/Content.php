<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Core;

use Ayre,
	Ayre\Core,
    Ayre\Behaviour\Loggable,
    Ayre\Behaviour\Publishable,
    Ayre\Behaviour\Searchable,
    Ayre\Behaviour\Trackable,
    Ayre\Behaviour\Versionable,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
*/
abstract class Content extends \Toast\Entity implements Loggable, Publishable, Searchable, Trackable, Versionable
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
     * @Column(type="string", nullable=true)
     */
	protected $subtype;
	
	/**
     * @OneToMany(targetEntity="\Ayre\Core\Structure\Node", mappedBy="content")
     */
	protected $nodes;
	
	public function __construct()
	{	
		$this->nodes	= new ArrayCollection();
		$this->actions	= new ArrayCollection();
		
		$this->__constructVersionable();
	}

	protected function _alterSlugMetadata($md)
	{
		$md['validator']->removeValidator('Toast\Validator\Set');
		return $md;
	}
			
	public function searchFields()
	{
		return [
			'name',
		];
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

	public function subname()
	{
		return $this->subtypeLabel;
	}

	public function type()
	{
		return \Ayre::type($this)->name;
	}

	public function typeLabel()
	{
		return \Ayre::type($this)->singular;
	}

	public function subtypeLabel()
	{
		return $this->subtype;
	}

	public function __toString()
	{
		return (string) $this->id;
	}
}