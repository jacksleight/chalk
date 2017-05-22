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
class Tag extends Entity implements Trackable, Searchable
{
	public static $chalkSingular = 'Tag';
    public static $chalkPlural   = 'Tags';
	public static $chalkIcon     = 'price-tag';
	
    use Trackable\Entity;
    use Searchable\Entity;

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
                ? \Chalk\str_slugify($slug)
                : $slug;
            return $this;
        }
        return $this->slug;
    }

    public function searchContent(array $content = [])
    {
        return [
            $this->name,
        ];
    }
}