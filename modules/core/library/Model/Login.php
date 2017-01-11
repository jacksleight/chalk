<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Model;

use Coast\Validator;

class Login extends \Toast\Entity
{
	protected $emailAddress;
	
	protected $password;

	protected static function _defineMetadata($class)
	{
		return array(
			'fields' => array(
				'emailAddress' => array(
					'type'		=> 'string',
					'validator'	=> (new Validator)
						->emailAddress(),
				),
				'password' => array(
					'type'		=> 'string',
				),
			),
		);
	}
}