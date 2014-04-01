<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

use Ayre,
    Coast\Model,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="class", type="string")
*/
abstract class Item extends Model
{
	const STATUS_DRAFT		= 'draft';
	const STATUS_PENDING	= 'pending';
	const STATUS_PUBLISHED	= 'published';
	const STATUS_ARCHIVED	= 'archived';
	
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
     * @ORM\Column(type="integer")
     */
	protected $version = 1;

	/**
     * @ORM\Column(type="string", length=10)
     */
	protected $status = self::STATUS_PENDING;
	
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
     * @Gedmo\Slug(fields={"name"})
     */
	protected $slug;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createDate;

    /**
     * @ORM\ManyToOne(targetEntity="Ayre\User")
     * @Gedmo\Blameable(on="create")
     */
    protected $createUser;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $modifyDate;

    /**
     * @ORM\ManyToOne(targetEntity="Ayre\User")
     * @Gedmo\Blameable(on="update")
     */
    protected $modifyUser;

	/**
     * @ORM\OneToOne(targetEntity="Ayre\Search", mappedBy="item")
     */
	protected $search;

	/**
     * @ORM\ManyToOne(targetEntity="Ayre\Item", inversedBy="children")
     */
	protected $parent;

	/**
     * @ORM\OneToMany(targetEntity="Ayre\Item", mappedBy="parent")
     */
	protected $children;

	/**
     * @ORM\OneToMany(targetEntity="Ayre\Action", mappedBy="item")
     */
	protected $actions;
	
	/**
     * @ORM\OneToMany(targetEntity="Ayre\Tree\Node", mappedBy="item")
     */
	protected $nodes;
	
	public static function search($phrase)
	{
		$conn = \Ayre::$instance->em->getConnection();
		
		$phrase = $conn->quote($phrase);
		return \JS\array_column($conn->query("
			SELECT s.id,
				MATCH(s.content) AGAINST ({$phrase} IN BOOLEAN MODE) AS score
			FROM search AS s
			WHERE MATCH(s.content) AGAINST ({$phrase} IN BOOLEAN MODE)
			ORDER BY score DESC
		")->fetchAll(), 'id');
	}

	public function __construct()
	{	
		$search = new \Ayre\Search();
		$search->item = $this;
		$this->search = $search;
	}
	
	public function getSmartLabel()
	{
		return isset($this->label)
			? $this->label
			: $this->name;
	}
	
	public function getSmartSlug()
	{
		if (isset($this->slug)) {
			return $this->slug;
		}
		$slug = \Coast\str_simplify(iconv('utf-8', 'ascii//translit//ignore', $this->smartLabel), '-');
		return strlen($slug)
			? strtolower($slug)
			: null;
	}
	
	public function getSearchContent()
	{
		return \Coast\array_filter_null(array(
			$this->name,
			$this->label,
		));
	}
}