<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core;

use Chalk\Chalk,
	Chalk\Core,
    Chalk\Behaviour\Loggable,
    Chalk\Behaviour\Publishable,
    Chalk\Behaviour\Searchable,
    Chalk\Behaviour\Trackable,
    Chalk\Behaviour\Versionable,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"content" = "Chalk\Core\Content"})
*/
abstract class Content extends \Toast\Entity implements Loggable, Publishable, Searchable, Trackable, Versionable
{
	public static $_chalkInfo = [
		'singular'	=> 'Content',
		'plural'	=> 'Content',
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
     * @Column(type="string")
     */
	protected $slug;
			
	/**
     * @Column(type="string", nullable=true)
     */
	protected $subtype;
	
	/**
     * @OneToMany(targetEntity="\Chalk\Core\Structure\Node", mappedBy="content")
     */
	protected $nodes;
		
	/**
     * @Column(type="boolean")
     */
	protected $isProtected = false;
	
	public function __construct()
	{	
		$this->nodes = new ArrayCollection();
		
		$this->__constructVersionable();
	}
			
	public function searchableContent()
	{
		return [
			$this->name,
		];
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

	public function clarifier($context = false, $parts = [])
	{	
		$type = $this->typeLabel;
		if (!$parts && isset($this->subtype)) {
			array_unshift($parts, $this->subtypeLabel);
		}
		if (!$context) {
			array_unshift($parts, $this->typeLabel);
		}
		return implode(' – ', $parts);
	}

	public function type()
	{
		return \Chalk\Chalk::info($this)->name;
	}

	public static function staticTypeLabel($type)
	{
		return \Chalk\Chalk::info($type)->singular;
	}

	public static function staticSubtypeLabel($subtype)
	{
		return $subtype;
	}

	public function typeLabel()
	{
		return static::staticTypeLabel(get_class($this));
	}

	public function subtypeLabel()
	{
		return static::staticSubtypeLabel($this->subtype);
	}

	public function restore()
	{
		$this->status = \Chalk\Chalk::STATUS_DRAFT;
		$this->archiveDate = null;
		return $this;
	}
}