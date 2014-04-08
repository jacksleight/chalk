<?php
/* 
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Validator;

class Set extends \Js\Validator
{
	const INVALID = 'validator_set_invalid';

	protected $_break = true;

	public function isValid($value)
	{
		$this->_resetErrors();

		if (!isset($value) || (is_array($value) && isset($value['error']) && $value['error'] == UPLOAD_ERR_NO_FILE)) {
			$this->_addError(self::INVALID);
			return false;
		}
		return true;
	}
}