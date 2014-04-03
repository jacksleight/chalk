<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity\Tree;

use Ayre\Entity,
	Coast\Model,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Tree(type="materializedPath")
*/
class Node extends Entity
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Gedmo\TreePathSource
     */
	protected $id;

    /**
     * @ORM\OneToOne(targetEntity="\Ayre\Entity\Tree", mappedBy="root")
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
     * @ORM\ManyToOne(targetEntity="\Ayre\Entity\Tree\Node", inversedBy="children")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Gedmo\TreeParent
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="\Ayre\Entity\Tree\Node", mappedBy="parent", cascade={"persist"})
     */
    protected $children;

	/**
     * @ORM\ManyToOne(targetEntity="\Ayre\Entity\Content", inversedBy="nodes")
     * @ORM\JoinColumn(nullable=false)
     */
	protected $content;

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

    public function parent(Entity\Tree\Node $value = null)
    {
        if (isset($value)) {
            $this->parent = $value;
            $value->children->add($this);
        }
        return $this->parent;
    }

    public function content(Entity\Content $value = null)
    {
        if (isset($value)) {
            $this->content = $value;
        }
        return $this->content;
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