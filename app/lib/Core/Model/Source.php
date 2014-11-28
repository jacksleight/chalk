<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Model;

use Respect\Validation\Validator;

class Source extends \Toast\Entity
{
	protected $html;

	protected static function _defineMetadata($class)
	{
		return array(
			'fields' => array(
				'html' => array(
					'type'	=> 'text',
				),
			),
		);
	}
}