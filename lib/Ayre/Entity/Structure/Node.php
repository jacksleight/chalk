<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity\Structure;

use Ayre\Entity,
    Coast\Model,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Node extends \Toast\Entity
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="\Ayre\Entity\Structure", inversedBy="nodes")
     * @JoinColumn(nullable=false)
     */
    protected $structure;

    /**
     * @Column(type="integer")
     */
    protected $sort = 0;

    /**
     * @Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @Column(type="string", nullable=true)
     */
    protected $slug;

    /**
     * @Column(type="string", nullable=true)
     */
    protected $path;

    /**
     * @ManyToOne(targetEntity="\Ayre\Entity\Structure\Node", inversedBy="children")
     * @JoinColumn(onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @OneToMany(targetEntity="\Ayre\Entity\Structure\Node", mappedBy="parent", cascade={"persist"})
     * @OrderBy({"sort" = "ASC"})
     */
    protected $children;

    /**
     * @ManyToOne(targetEntity="\Ayre\Entity\Content", inversedBy="nodes")
     * @JoinColumn(nullable=true)
     */
    protected $content;

    public function __construct()
    {
        $this->children = new ArrayCollection();

        global $em;
        $this->content($em->getRepository('Ayre\Entity\Page')->find(1));
    }

    public function isRoot()
    {
        return !isset($this->parent);
    }

    public function root()
    {
        return isset($this->parent)
            ? $this->parent->root
            : $this;
    }

    public function content(Entity\Content $value = null)
    {
        if (isset($value)) {
            $this->content = $value;
            $this->content->nodes->add($this);
        }
        return $this->content;
    }

    public function nameSmart()
    {
        return isset($this->name)
            ? $this->name
            : $this->content->last->name;
    }

    public function slugSmart()
    {
        return isset($this->slug)
            ? $this->slug
            : $this->content->last->slug;
    }

    public function name($name = null)
    {
        if (isset($name)) {
            $this->name = $name;
            $this->slug($this->slug);
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

    public function parent(Entity\Structure\Node $parent = null)
    {
        if (isset($parent)) {
            $this->parent    = $parent;
            $this->structure = $parent->structure;
            $this->sort      = $this->parent->children->count();
            $parent->children->add($this);
        }
        return $this->parent;
    }

    public function __clone()
    {
        if (isset($this->children)) {
            $this->children = clone $this->children;
            foreach ($this->children as $child) {
                $this->children->removeElement($child);
                $child = clone $child;
                $child->parent($this);
            }
        }
    }

    public function parents()
    {
        return isset($this->parent)
            ? array_merge($this->parent->parents, [$this])
            : [$this];
    }

    /**
     * @Column(type="integer", nullable=true)
     */
    protected $root_id;

    /**
     * @Column(name="`left`", type="integer", nullable=true)
     */
    protected $left;

    /**
     * @Column(name="`right`", type="integer", nullable=true)
     */
    protected $right;

    /**
     * @Column(type="integer", nullable=true)
     */
    protected $level;
}
