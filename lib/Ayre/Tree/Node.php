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
class Node extends Model implements Behaviour\Trackable
{
    use Behaviour\Trackable\Implementation;

	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Gedmo\TreePathSource
     */
	protected $id;

	/**
     * @ORM\Column(type="string")
	 * @Gedmo\TreePath(separator="/", endsWithSeparator=false)
	 */
	protected $path;

    /**
     * @ORM\ManyToOne(targetEntity="Ayre\Tree\Node", inversedBy="children")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Gedmo\TreeParent
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Ayre\Tree\Node", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Ayre\Tree", inversedBy="nodes")
     */
    protected $tree;

	/**
     * @ORM\ManyToOne(targetEntity="Ayre\Item", inversedBy="nodes")
     */
	protected $item;

	public function createPath()
	{
		$path = new \Ayre\Tree\Revision\Node\Path();
		$path->node = $this;
		$this->paths[] = $path;
		return $path;
	}
}