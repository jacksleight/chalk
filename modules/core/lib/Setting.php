<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Core;
use Coast\Validator;

/**
 * @Entity
*/
class Setting extends \Toast\Entity
{
	public static $chalkSingular = 'Setting';
	public static $chalkPlural   = 'Settings';
	
	/**
     * @Id
     * @Column(type="string")
     */
	protected $name;
            
    /**
     * @Column(type="text", nullable=true)
     */
    protected $value;
}