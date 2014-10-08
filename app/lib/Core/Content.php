<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core;

use Chalk\Chalk,
	Chalk\Core,
    Chalk\Behaviour\Loggable,
    Chalk\Behaviour\Publishable,
    Chalk\Behaviour\Searchable,
    Chalk\Behaviour\Trackable,
    Chalk\Behaviour\Versionable,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"core_content" = "Chalk\Core\Content"})
*/
abstract class Content extends \Toast\Entity implements Loggable, Publishable, Searchable, Trackable, Versionable
{
	public static $info = [
		'singular'	=> 'Content',
		'plural'	=> 'Content',
	];
	
    use Publishable\Entity,
    	Trackable\Entity,
    	Versionable\Entity {
        	Versionable\Entity::__construct as __constructVersionable;
    	}
	
	/**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
		
	/**
     * @Column(type="string")
     */
	protected $name;
		
	/**
     * @Column(type="string")
     */
	protected $slug;
		
	/**
     * @Column(type="string", nullable=true)
     */
	protected $subtype;
	
	/**
     * @OneToMany(targetEntity="\Chalk\Core\Structure\Node", mappedBy="content")
     */
	protected $nodes;
	
	public function __construct()
	{	
		$this->nodes = new ArrayCollection();
		
		$this->__constructVersionable();
	}

	protected function _alterSlugMetadata($md)
	{
		$md['validator']->removeValidator('Toast\Validator\Set');
		return $md;
	}
			
	public function searchFields()
	{
		return [
			'name',
		];
	}

	public function name($name = null)
	{
		if (isset($name)) {
			$this->name = $name;
			$this->slug($this->name);
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

	public function subname($context = false)
	{
		$subname = '';
		if (!$context) {
			$subname .= $this->typeLabel;
			if (isset($this->subtype)) {
				$subname .= ' – ';
			}
		}
		$subname .= $this->subtypeLabel;
		return $subname;
	}

	public function type()
	{
		return \Chalk\Chalk::entity($this)->name;
	}

	public function typeLabel()
	{
		return \Chalk\Chalk::entity($this)->singular;
	}

	public function subtypeLabel()
	{
		return $this->subtype;
	}

	public function __toString()
	{
		return (string) $this->id;
	}

	public function restore()
	{
		$this->status = \Chalk\Chalk::STATUS_DRAFT;
		$this->archiveDate = null;
		return $this;
	}
}