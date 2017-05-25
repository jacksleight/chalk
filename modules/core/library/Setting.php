<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Core;
use Chalk\Entity;
use Coast\Validator;

/**
 * @Entity
*/
class Setting extends Entity
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