<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Tree;

use Ayre,
    Ayre\Behaviour,
	Coast\Model,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
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
     * @ORM\Column(type="string")
     */
    protected $slug = '1';

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
     * @ORM\ManyToOne(targetEntity="Ayre\Item", inversedBy="nodes")
     * @ORM\JoinColumn(nullable=false)
     */
	protected $item;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->_refreshSlug();
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
            $this->_refreshSlug();
        }
        return $this->parent;
    }

    public function item(\Ayre\Item $value = null)
    {
        if (isset($value)) {
            $this->item = $value;
            $this->_refreshSlug();
        }
        return $this->item;
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

    protected function _refreshSlug()
    {
        $this->slug = implode('/', array_map(function($node) {
            return isset($node->item)
                ? $node->item->slug
                : null;
        }, $this->ancestors));
    }
}