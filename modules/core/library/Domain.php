<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Core;
use Chalk\Entity;
use Chalk\Core\Behaviour\Trackable;
use Coast\Model;
use Doctrine\Common\Collections\ArrayCollection;
use Coast\Validator;

/**
 * @Entity
 * @Table(
 *     uniqueConstraints={@UniqueConstraint(columns={"name"})}
 * )
*/
class Domain extends Entity implements Trackable
{
	public static $chalkSingular = 'Site';
    public static $chalkPlural   = 'Sites';
	public static $chalkIcon     = 'publish';
	
    use Trackable\Entity;

	protected static function _defineMetadata($class)
	{
        return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
			'fields' => array(
				'name' => array(
					'validator'	=> (new Validator)
						->hostname(),
				),
			),
			'associations' => array(
				'structures' => array(
					'validator'	=> (new Validator)
						->count(1),
				),
			),
		));
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
    protected $label;
    
    /**
     * @Column(type="string")
     */
    protected $emailAddress;
	
    /**
     * @Column(type="text", nullable=true)
     */
	protected $head;
	
    /**
     * @Column(type="text", nullable=true)
     */
	protected $body;

    /**
     * @ManyToMany(targetEntity="\Chalk\Core\Structure", inversedBy="domains")
     */
    protected $structures;

	public function __construct()
	{	
		parent::__construct();

		$this->structures = new ArrayCollection();
	}

    public function previewName()
    {
        return $this->label;
    }
}