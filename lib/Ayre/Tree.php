<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

use Ayre,
    Ayre\Behaviour,
	Coast\Model,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
*/
class Tree extends Model implements Behaviour\Trackable, Behaviour\Versionable, Behaviour\Publishable, Behaviour\Loggable
{
    use Behaviour\Trackable\Implementation;
    use Behaviour\Versionable\Implementation;
    use Behaviour\Publishable\Implementation;

	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
	
    /**
     * @ORM\Column(type="string")
     */
	protected $name;

	/**
     * @ORM\OneToOne(targetEntity="Ayre\Tree\Node", inversedBy="tree", cascade={"persist"})
     */
	protected $root;

	/**
     * @ORM\OneToMany(targetEntity="Ayre\Domain", mappedBy="tree")
     */
	protected $domains;

	/**
     * @ORM\OneToMany(targetEntity="Ayre\Menu", mappedBy="tree")
     */
	protected $menus;

	/**
     * @ORM\ManyToOne(targetEntity="Ayre\Tree", inversedBy="versions")
     */
	protected $master;

	/**
     * @ORM\OneToMany(targetEntity="Ayre\Tree", mappedBy="master")
     */
	protected $versions;

	/**
     * @ORM\OneToMany(targetEntity="Ayre\Action", mappedBy="tree")
     */
	protected $actions;

	public function __construct()
	{	
		$this->nodes	= new ArrayCollection();
		$this->versions	= new ArrayCollection();
		$this->actions	= new ArrayCollection();
		
		$this->master = $this;
		$this->versions->add($this);

		$this->root = new \Ayre\Tree\Node();
		$this->root->tree = $this;
	}

	public function __clone()
	{
		$this->root = clone $this->root;
	}
}