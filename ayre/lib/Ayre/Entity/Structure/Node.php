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
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Slug(fields={"name"}, unique=false)
     */
    protected $slug;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Slug(handlers={
     *     @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\TreeSlugHandler", options={
     *         @Gedmo\SlugHandlerOption(name="parentRelationField", value="parent"),
     *         @Gedmo\SlugHandlerOption(name="separator", value="/")
     *     })
     * }, fields={"slug"}, unique=false)
     */
    protected $slugPath;

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

    public function parent(Entity\Structure\Node $value = null)
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
            $this->name = $this->content->name;
        }
        return $this->content;
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
            ? array_merge([$this], $this->parent->parents)
            : [$this];
    }

    public function slugPathReal()
    {
        $parts = explode('/', $this->slugPath);
        array_shift($parts);
        return implode('/', $parts);
    }
}