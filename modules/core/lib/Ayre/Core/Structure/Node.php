<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Core\Structure;

use Ayre\Core\Content,
    Ayre\Core\Structure\Node,
    Coast\Model,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Node extends \Toast\Entity
{
    const SORT_MAX = 99999;

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="\Ayre\Core\Structure", inversedBy="nodes")
     * @JoinColumn(nullable=false)
     */
    protected $structure;

    /**
     * @ManyToOne(targetEntity="\Ayre\Core\Structure\Node", inversedBy="children")
     * @JoinColumn(onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @OneToMany(targetEntity="\Ayre\Core\Structure\Node", mappedBy="parent", cascade={"persist"})
     * @OrderBy({"sort" = "ASC"})
     */
    protected $children;

    /**
     * @Column(type="integer")
     */
    protected $sort = self::SORT_MAX;

    /**
     * @Column(name="`left`", type="integer")
     */
    protected $left = -1;

    /**
     * @Column(name="`right`", type="integer")
     */
    protected $right = -1;

    /**
     * @Column(type="integer")
     */
    protected $depth = -1;

    /**
     * @ManyToOne(targetEntity="\Ayre\Core\Content", inversedBy="nodes")
     * @JoinColumn(nullable=true)
     */
    protected $contentMaster;

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

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function isRoot()
    {
        return !isset($this->parent);
    }

    public function contentMaster(Content $content = null)
    {
        if (isset($content)) {
            if (!$content->isMaster()) {
                throw new \Ayre\Exception("Content master can only be set to a master content version");
            }
            $this->contentMaster = $content;
            $this->contentMaster->nodes->add($this);
        }
        return $this->contentMaster;
    }

    public function nameSmart()
    {
        return isset($this->name)
            ? $this->name
            : $this->content->name;
    }

    public function slugSmart()
    {
        return isset($this->slug)
            ? $this->slug
            : $this->content->slug;
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

    public function iterator($include = false)
    {
        return new \RecursiveIteratorIterator(
            new \Ayre\Core\Structure\Iterator($include ? [$this] : $this->children),
            \RecursiveIteratorIterator::SELF_FIRST);
    }

    public function content()
    {
        return $this->contentMaster->last;
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
