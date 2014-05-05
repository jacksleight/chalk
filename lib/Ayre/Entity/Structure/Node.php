<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity\Structure;

use Ayre\Entity,
	Coast\Model,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Tree(type="nested")
*/
class Node extends \Toast\Entity
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Gedmo\TreePathSource
     */
	protected $id;

    /**
     * @ORM\OneToOne(targetEntity="\Ayre\Entity\Structure", mappedBy="root")
     */
    protected $structure;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\TreeRoot
     */
    protected $root_id;

    /**
     * @ORM\Column(name="`left`", type="integer")
     * @Gedmo\TreeLeft
     */
    protected $left;

    /**
     * @ORM\Column(name="`right`", type="integer")
     * @Gedmo\TreeRight
     */
    protected $right;

    /**
     * @ORM\Column(type="integer")
     * @Gedmo\TreeLevel
     */
    protected $level;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort = 0;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $slug;

    /**
     * @ORM\Column(type="string")
     */
    protected $path;

    /**
     * @ORM\ManyToOne(targetEntity="\Ayre\Entity\Structure\Node", inversedBy="children")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Gedmo\TreeParent
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="\Ayre\Entity\Structure\Node", mappedBy="parent", cascade={"persist"})
     * @ORM\OrderBy({"left" = "ASC"})
     */
    protected $children;

	/**
     * @ORM\ManyToOne(targetEntity="\Ayre\Entity\Content", inversedBy="nodes")
     * @ORM\JoinColumn(nullable=true)
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

    public function parent(Entity\Structure\Node $value = null)
    {
        if (isset($value)) {
            $this->parent = $value;
            $value->children->add($this);
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

    public function slugPathReal()
    {
        $parts = explode('/', $this->slugPath);
        array_shift($parts);
        return implode('/', $parts);
    }
}