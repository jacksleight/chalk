<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Model;

use Respect\Validation\Validator;

class PasswordReset extends \Toast\Entity
{
	protected $password;

	protected static function _defineMetadata($class)
	{
		return array(
			'fields' => array(
				'password' => array(
					'type'		=> 'string',
				),
			),
		);
	}
}