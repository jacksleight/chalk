<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Structure;

use Chalk\Core\Content,
    Chalk\Core\Structure\Node,
    Coast\Model,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(indexes={@Index(columns={"left", "right", "depth"})})
*/
class Node extends \Toast\Entity
{
    public static $_chalkInfo = [
        'singular'  => 'Node',
        'plural'    => 'Nodes',
    ];

    const SORT_MAX = 99999;

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="\Chalk\Core\Structure", inversedBy="nodes")
     * @JoinColumn(nullable=false)
     */
    protected $structure;

    /**
     * @Column(type="integer")
     */
    protected $parentId;

    /**
     * @ManyToOne(targetEntity="\Chalk\Core\Structure\Node", inversedBy="children")
     * @JoinColumn(onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @OneToMany(targetEntity="\Chalk\Core\Structure\Node", mappedBy="parent", cascade={"persist"})
     * @OrderBy({"sort" = "ASC"})
     */
    protected $children;

    /**
     * @Column(type="integer")
     */
    protected $sort = PHP_INT_MAX;

    /**
     * @Column(name="`left`", type="integer")
     */
    protected $left = PHP_INT_MAX;

    /**
     * @Column(name="`right`", type="integer")
     */
    protected $right = PHP_INT_MAX;

    /**
     * @Column(type="integer")
     */
    protected $depth = PHP_INT_MAX;

    /**
     * @ManyToOne(targetEntity="\Chalk\Core\Content", inversedBy="nodes")
     * @JoinColumn(nullable=true)
     */
    protected $content;

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
     * @Column(type="boolean")
     */
    protected $isHidden = false;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function isRoot()
    {
        return !isset($this->parent);
    }

    public function content(Content $content = null)
    {
        if (isset($content)) {
            if (!$content->isMaster()) {
                throw new \Chalk\Exception("Content can only be set to a master content version");
            }
            $this->content = $content;
            $this->content->nodes->add($this);
        }
        return $this->content;
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

    public function parent(Node $parent = null)
    {
        if (isset($parent)) {
            $this->parent    = $parent;
            $this->structure = $parent->structure;
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
}
