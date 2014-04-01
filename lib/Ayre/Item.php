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
 * @ORM\DiscriminatorColumn(name="class", type="string")
*/
abstract class Item extends Model implements Behaviour\Trackable, Behaviour\Versionable, Behaviour\Publishable
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
     * @Gedmo\Slug(fields={"name"})
     */
	protected $slug;

	/**
     * @ORM\OneToOne(targetEntity="Ayre\Search", mappedBy="item")
     */
	protected $search;

	/**
     * @ORM\ManyToOne(targetEntity="Ayre\Item", inversedBy="followers")
     */
	protected $master;

	/**
     * @ORM\OneToMany(targetEntity="Ayre\Item", mappedBy="master")
     */
	protected $followers;

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