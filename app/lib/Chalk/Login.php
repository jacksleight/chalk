<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

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
					'validator'	=> new \Toast\Validator\Chain(array(
						new \Toast\Validator\EmailAddress(),
					)),
				),
				'password' => array(
					'type'		=> 'string',
				),
			),
		);
	}
}