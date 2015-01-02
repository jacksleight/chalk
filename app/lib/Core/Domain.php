<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core;

use Chalk\Core,
    Chalk\Behaviour\Trackable,
	Coast\Model,
	Doctrine\Common\Collections\ArrayCollection;
use Respect\Validation\Validator;

/**
 * @Entity
*/
class Domain extends \Toast\Entity implements Trackable
{
	public static $_chalkInfo = [
		'singular'	=> 'Domain',
		'plural'	=> 'Domains',
	];
	
    use Trackable\Entity;

	protected static function _defineMetadata($class)
	{
		return array(
			'fields' => array(
				'name' => array(
					'validator'	=> Validator::domain(),
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
     * @ManyToOne(targetEntity="\Chalk\Core\Structure", inversedBy="domains", cascade={"persist"})
     * @JoinColumn(nullable=false)
     */
    protected $structure;
}