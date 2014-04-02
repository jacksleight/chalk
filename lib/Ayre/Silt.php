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
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="_discriminator", type="string")
*/
abstract class Silt extends Model implements Behaviour\Trackable, Behaviour\Versionable, Behaviour\Publishable, Behaviour\Loggable
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
    protected $type;
	
	/**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $subtype;
	
	/**
     * @ORM\Column(type="string")
     */
	protected $name;

	/**
     * @ORM\Column(type="string", nullable=true)
     */
	protected $label;
	
	/**
     * @ORM\Column(type="string")
     * @Gedmo\Slug(fields={"name"}, unique=false)
     */
	protected $slug;

	/**
     * @ORM\OneToOne(targetEntity="Ayre\Search", mappedBy="silt", cascade={"persist"})
     */
	protected $search;
	
	/**
     * @ORM\OneToMany(targetEntity="Ayre\Tree\Node", mappedBy="silt")
     */
	protected $nodes;

	/**
     * @ORM\ManyToOne(targetEntity="Ayre\Silt", inversedBy="versions")
     */
	protected $master;

	/**
     * @ORM\OneToMany(targetEntity="Ayre\Silt", mappedBy="master")
     */
	protected $versions;

	/**
     * @ORM\OneToMany(targetEntity="Ayre\Action", mappedBy="silt")
     */
	protected $actions;

	public function __construct()
	{	
		$this->nodes	= new ArrayCollection();
		$this->versions	= new ArrayCollection();
		$this->actions	= new ArrayCollection();
		
		$this->master = $this;
		$this->versions->add($this);

		$search = new \Ayre\Search();
		$search->silt = $this;
		$this->search = $search;
	}
	
	public function smartLabel()
	{
		return isset($this->label)
			? $this->label
			: $this->name;
	}
	
	public function smartSlug()
	{
		if (isset($this->slug)) {
			return $this->slug;
		}
		$slug = \Coast\str_simplify(iconv('utf-8', 'ascii//translit//ignore', $this->smartLabel), '-');
		return strlen($slug)
			? strtolower($slug)
			: null;
	}
	
	public function searchContent()
	{
		return \Coast\array_filter_null(array(
			$this->name,
			$this->label,
		));
	}
}