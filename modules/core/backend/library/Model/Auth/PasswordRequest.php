<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Model\Auth;

use Chalk\Core\Backend\Model;
use Coast\Validator;

class PasswordRequest extends Model
{
	protected $emailAddress;

	protected static function _defineMetadata($class)
	{
        return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
			'fields' => array(
				'emailAddress' => array(
					'type'		=> 'string',
					'validator'	=> (new Validator)
						->emailAddress(),
				),
			),
		));
	}
}