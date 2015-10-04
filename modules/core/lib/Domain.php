<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Core,
    Chalk\Core\Behaviour\Trackable,
	Coast\Model,
	Doctrine\Common\Collections\ArrayCollection;
use Coast\Validator;

/**
 * @Entity
*/
class Domain extends \Toast\Entity implements Trackable
{
	public static $chalkSingular = 'Site';
	public static $chalkPlural   = 'Sites';
	
    use Trackable\Entity;

	protected static function _defineMetadata($class)
	{
		return array(
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
		);
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
}