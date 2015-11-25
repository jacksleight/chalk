<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\App as Chalk;
use Chalk\Core;
use Chalk\Core\Behaviour\Loggable;
use Chalk\Core\Behaviour\Publishable;
use Chalk\Core\Behaviour\Searchable;
use Chalk\Core\Behaviour\Trackable;
use Chalk\Core\Behaviour\Versionable;
use Coast\Filter;
use Toast\Wrapper;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"content" = "Chalk\Core\Content"})
*/
abstract class Content extends \Toast\Entity implements Publishable, Searchable, Trackable
{
	public static $chalkSingular = 'Content';
    public static $chalkPlural   = 'Content';
	public static $chalkIcon     = 'content';
	
    use Publishable\Entity {
            Publishable\Entity::_defineMetadata as _defineMetadataPublishable;
        }
    use Trackable\Entity;
    // use Versionable\Entity {
    //     	Versionable\Entity::__construct as __constructVersionable;
    // 	}
	
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
     * @Column(type="json_array", nullable=true)
     */
    protected $data = [];

    protected $dataJson;
    
    /**
     * @ManyToMany(targetEntity="\Chalk\Core\Tag", inversedBy="contents", cascade={"persist"})
     */
    protected $tags;
	
	/**
     * @OneToMany(targetEntity="\Chalk\Core\Structure\Node", mappedBy="content")
     */
	protected $nodes;
		
	/**
     * @Column(type="boolean")
     */
	protected $isProtected = false;
	
    protected static function _defineMetadata($class)
    {
        return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
            'fields' => array(
                'dataJson' => [
                    'type'     => 'text',
                    'nullable' => true,
                ],
                'tagsText' => [
                    'type'     => 'string',
                    'nullable' => true,
                ],
            ),
        ), self::_defineMetadataPublishable($class));
    }

	public function __construct()
	{	
        $this->tags  = new ArrayCollection();
		$this->nodes = new ArrayCollection();
		
		// $this->__constructVersionable();
	}

    public function dataJson($value = null)
    {
        if (func_num_args() > 0) {
            $this->dataJson = $value;
            $this->data = json_decode($this->dataJson, true);
            return $this;
        } if (!isset($this->dataJson)) {
            $this->dataJson = json_encode($this->data, JSON_PRETTY_PRINT);
        }
        return $this->dataJson;
    }

    protected function _postValidate()
    {
        if (isset($this->dataJson)) {
            if (json_decode($this->dataJson) === null) {
                $this->addError('dataJson', 'Please enter valid JSON');
            }
        }
    }
			
	public function searchableContent()
	{
		return [
			$this->name,
		];
	}

    public function name($name = null)
    {
        if (func_num_args() > 0) {
            $this->name = $name;
            $this->slug($this->name);
            return $this;
        }
        return $this->name;
    }

    public function slug($slug = null)
    {
        if (func_num_args() > 0) {
            $this->slug = isset($slug)
                ? strtolower(\Coast\str_slugify(iconv('utf-8', 'ascii//translit//ignore', $slug)))
                : $slug;
            return $this;
        }
        return $this->slug;
    }

	public function previewText($context = false, $parts = [])
	{	
		$type = $this->typeLabel;
		if (!$parts && isset($this->subtype)) {
			array_unshift($parts, $this->subtypeLabel);
		}
		if (!$context) {
			array_unshift($parts, $this->typeLabel);
		}
		return $parts;
	}

	public function type()
	{
		return Chalk::info($this)->name;
	}

	public static function staticTypeLabel($type)
	{
		return Chalk::info($type)->singular;
	}

	public static function staticSubtypeLabel($subtype)
	{
		return $subtype;
	}

	public function typeLabel()
	{
		return static::staticTypeLabel(get_class($this));
	}

	public function subtypeLabel()
	{
		return static::staticSubtypeLabel($this->subtype);
	}

	public function restore()
	{
		$this->status = Chalk::STATUS_DRAFT;
		$this->archiveDate = null;
		return $this;
	}

    public function tagsText($tagsText = null)
    {
        if (func_num_args() > 0) {
            if (!isset($tagsText)) {
                $this->tags->clear();
                return $this;
            }
            $split = explode(',', $tagsText);
            $names = [];
            foreach ($split as $name) {
                $names[strtolower(\Coast\str_slugify(iconv('utf-8', 'ascii//translit//ignore', $name)))] = trim($name);
            }
            $tags = Wrapper::$em->__invoke('core_tag')->all([
                'slugs' => array_keys($names),
            ]);
            foreach ($tags as $tag) {
                if (isset($names[$tag->slug])) {
                    unset($names[$tag->slug]);
                }
            }
            foreach ($names as $slug => $name) {
                $tag = new Tag();
                $tag->fromArray([
                    'name' => $name,
                ]);
                $tags[] = $tag;
            }
            foreach ($tags as $tag) {
                if (!$this->tags->contains($tag)) {
                    $this->tags->add($tag);
                }
            }
            foreach ($this->tags as $tag) {
                if (!in_array($tag, $tags, true)) {
                    $this->tags->removeElement($tag);
                }
            }
            return $this;
        }
        return implode(', ', array_map(function($tag) {
            return $tag->name;
        }, $this->tags->toArray()));
    }
}