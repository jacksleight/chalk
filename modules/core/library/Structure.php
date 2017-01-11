<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
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
 * @Table(
 *     uniqueConstraints={@UniqueConstraint(columns={"slug"})}
 * )
*/
class Structure extends \Toast\Entity implements Trackable
{
	public static $chalkSingular = 'Structure';
	public static $chalkPlural   = 'Structures';
    
    // use Publishable\Entity;
    use Trackable\Entity;
    // use Versionable\Entity {
    //     	Versionable\Entity::__construct as __constructVersionable;
    // 	}

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
    protected $path;
	
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

		// $this->__constructVersionable();
	}

	public function root()
	{
		return $this->nodes->first();
	}

    public function name($name = null)
    {
        if (func_num_args() > 0) {
            $this->name = $name;
            $this->slug($this->name);
            return $this;
        }
        return $this->name;
    }

    public function slug($slug = null)
    {
        if (func_num_args() > 0) {
            $this->slug = isset($slug)
                ? strtolower(\Coast\str_slugify(iconv('utf-8', 'ascii//translit//ignore', $slug)))
                : $slug;
            return $this;
        }
        return $this->slug;
    }
}