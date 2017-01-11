<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Core,
    Chalk\Core\Behaviour\Trackable,
    Chalk\Core\Behaviour\Searchable,
	Doctrine\Common\Collections\ArrayCollection;
use Coast\Validator;

/**
 * @Entity
 * @Table(
 *     uniqueConstraints={@UniqueConstraint(columns={"slug"})}
 * )
*/
class Tag extends \Toast\Entity implements Trackable, Searchable
{
	public static $chalkSingular = 'Tag';
	public static $chalkPlural   = 'Tags';
	
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
     * @ManyToMany(targetEntity="\Chalk\Core\Content", mappedBy="tags")
     **/
    protected $contents;

    public function __construct()
    {   
        $this->contents = new ArrayCollection();
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

    public function searchableContent()
    {
        return [
            $this->name,
        ];
    }
}