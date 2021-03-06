<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Chalk;
use Chalk\Core;
use Chalk\Entity;
use Chalk\Core\Behaviour\Publishable;
use Chalk\Core\Behaviour\Searchable;
use Chalk\Core\Behaviour\Tagable;
use Chalk\Core\Behaviour\Trackable;
use Chalk\Core\Behaviour\Duplicateable;
use Coast\Filter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;

/**
 * @Entity
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"content" = "Chalk\Core\Content"})
*/
abstract class Content extends Entity implements Publishable, Searchable, Tagable, Trackable, Duplicateable
{
	public static $chalkSingular = 'Content';
    public static $chalkPlural   = 'Content';
    public static $chalkIcon     = 'content';
	public static $chalkIs       = [
        'tagable' => false,
    ];

    use Publishable\Entity;
    use Searchable\Entity;
    use Tagable\Entity;
    use Trackable\Entity;
    use Duplicateable\Entity;

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
     * @OneToMany(targetEntity="\Chalk\Core\Structure\Node", mappedBy="content", cascade={"persist", "remove"})
     */
	protected $nodes;

	/**
     * @Column(type="boolean")
     */
	protected $isProtected = false;

    protected static function _defineMetadata($class)
    {
        return \Coast\array_merge_smart(
            parent::_defineMetadata($class),
            array(
                'fields' => array(
                    'dataJson' => [
                        'type'     => 'text',
                        'nullable' => true,
                    ],
                ),
            ),
            self::_publishable_defineMetadata($class),
            self::_tagable_defineMetadata($class)
        );
    }

	public function __construct()
	{
		$this->nodes = new ArrayCollection();
		$this->_tagable_construct();
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

    public function duplicate()
    {
        $entity = clone $this;
        $entity->fromArray([
            'name' => "{$entity->name} (copy)",
        ]);
        $this->_trackable_duplicate($entity);
        $this->_publishable_duplicate($entity);
        return $entity;
    }

    public function searchContent(array $content = [])
    {
        return array_merge([
            $this->name,
        ], $content);
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
                ? \Coast\str_slugify($slug)
                : $slug;
            return $this;
        }
        return $this->slug;
    }

	public function previewText($context = false)
	{
        $parts = parent::previewText($context);
		if (isset($this->subtype)) {
			$parts[] = $this->subtypeLabel;
		}
        return $parts;
	}

	public static function staticSubtypeLabel($subtype)
	{
		return $subtype;
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

    public function isNode()
    {
        if ($this->nodes instanceof ArrayCollection) {
            return isset($this->nodes[0]);
        } else if ($this->nodes instanceof PersistentCollection) {
            return $this->nodes->isInitialized() && isset($this->nodes[0]);
        } else {
            return false;
        }
    }
}