<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Core\Structure\Node;
use Chalk\Core\Behaviour\Trackable;
use Chalk\Core\Behaviour\Publishable;
use Chalk\Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(
 *     uniqueConstraints={@UniqueConstraint(columns={"slug"})}
 * )
*/
class Structure extends Entity implements Trackable
{
	public static $chalkSingular = 'Structure';
    public static $chalkPlural   = 'Structures';
	public static $chalkIcon     = 'structure';
    
    use Trackable\Entity;

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
                ? \Coast\str_slugify($slug)
                : $slug;
            return $this;
        }
        return $this->slug;
    }

    public function previewText($context = false)
    {
        $parts = parent::previewText($context);
        if (isset($this->path)) {
            $parts[] = "/{$this->path}";
        }
        return $parts;
    }
}