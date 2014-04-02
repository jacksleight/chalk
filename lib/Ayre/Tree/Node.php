<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Tree;

use Ayre,
	Coast\Model,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\MaterializedPathRepository")
 * @Gedmo\Tree(type="materializedPath")
*/
class Node extends Model
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Gedmo\TreePathSource
     */
	protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Ayre\Tree", mappedBy="root")
     */
    protected $tree;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\TreePath(separator="/", endsWithSeparator=false)
     */
    protected $path;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $slug;

    /**
     * @ORM\ManyToOne(targetEntity="Ayre\Tree\Node", inversedBy="children")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Gedmo\TreeParent
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Ayre\Tree\Node", mappedBy="parent", cascade={"persist"})
     */
    protected $children;

	/**
     * @ORM\ManyToOne(targetEntity="Ayre\Silt", inversedBy="nodes")
     * @ORM\JoinColumn(nullable=false)
     */
	protected $silt;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function root()
    {
        return isset($this->parent)
            ? $this->parent->root
            : $this;
    }

    public function parent(\Ayre\Tree\Node $value = null)
    {
        if (isset($value)) {
            $this->parent = $value;
            $value->children->add($this);
        }
        return $this->parent;
    }

    public function silt(\Ayre\Silt $value = null)
    {
        if (isset($value)) {
            $this->silt = $value;
        }
        return $this->silt;
    }

    public function __clone()
    {
        $this->children = clone $this->children;
        foreach ($this->children as $child) {
            $this->children->removeElement($child);
            $child = clone $child;
            $child->parent($this);
        }
    }

    public function ancestors()
    {
        return isset($this->parent)
            ? array_merge([$this], $this->parent->ancestors)
            : [$this];
    }
}