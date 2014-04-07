<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity;

use Ayre,
	Ayre\Entity,
    Ayre\Behaviour\Loggable,
    Ayre\Behaviour\Publishable,
    Ayre\Behaviour\Searchable,
    Ayre\Behaviour\Trackable,
    Ayre\Behaviour\Versionable,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="entity_type", type="string")
*/
abstract class Content extends Entity implements Loggable, Publishable, Searchable, Trackable, Versionable
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
    protected $type;
	
	/**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $subtype;
	
	/**
     * @ORM\Column(type="string")
     */
	protected $name;

	/**
     * @ORM\Column(type="string", nullable=true)
     */
	protected $label;
	
	/**
     * @ORM\Column(type="string")
     * @Gedmo\Slug(fields={"name"}, unique=false)
     */
	protected $slug;
	
	/**
     * @ORM\OneToMany(targetEntity="\Ayre\Entity\Tree\Node", mappedBy="content")
     */
	protected $nodes;
	
	public function __construct()
	{	
		$this->type		= Ayre::type($this)->type;
		$this->nodes	= new ArrayCollection();
		$this->actions	= new ArrayCollection();
		
		$this->__constructVersionable();
	}
			
	public function searchFields()
	{
		return [
			'name',
			'label',
		];
	}
}