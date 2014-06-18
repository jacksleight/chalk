<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Core;

use Ayre\Core,
    Ayre\Behaviour\Trackable,
	Coast\Model,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Domain extends \Toast\Entity implements Trackable
{
	public static $info = [
		'singular'	=> 'Domain',
		'plural'	=> 'Domains',
	];
	
    use Trackable\Implementation;

	protected static function _defineMetadata($class)
	{
		return array(
			'fields' => array(
				'name' => array(
					'validator'	=> new \Toast\Validator\Chain(array(
						new \Toast\Validator\Hostname(),
					)),
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
     * @ManyToOne(targetEntity="\Ayre\Core\Structure", inversedBy="domains", cascade={"persist"})
     * @JoinColumn(nullable=false)
     */
    protected $structure;
}