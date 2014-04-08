<?php
/* 
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Validator;

class Url extends \Js\Validator
{
	const INVALID = 'validator_url_invalid';

	protected $_break = true;

	public function isValid($value)
	{
		$this->_resetErrors();
		if (!isset($value)) {
			return true;
		}

		if ($value instanceof \Coast\Url\Invalid || $value->getScheme() === null) {
			$this->_addError(self::INVALID);
			return false;
		}
		return true;
	}
}