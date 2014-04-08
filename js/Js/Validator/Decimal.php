<?php
/* 
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Validator;

class Decimal extends \Js\Validator
{
	const INVALID = 'validator_decimal_invalid';

	protected $_break = true;

	public function isValid($value)
	{
		$this->_resetErrors();
		if (!isset($value)) {
			return true;
		}

		$value = (string) $value;
		if (!is_numeric($value)) {
			$this->_addError(self::INVALID);
			return false;
		}
		return true;
	}
}