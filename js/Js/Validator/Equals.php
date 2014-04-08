<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Validator;

class Equals extends \Js\Validator
{
	const INVALID = 'validator_equals_invalid';

	protected $_properties = array('value');
	protected $_value = null;

	public function __construct($value)
	{
		$this->setValue($value);
	}

	public function setValue($value)
	{
		$this->_value = $value;
		return $this;
	}

	public function isValid($value)
	{
		$this->_resetErrors();
		if (!isset($value)) {
			return true;
		}

		if ($value != $this->_value) {
			$this->_addError(self::INVALID);
			return false;
		}
		return true;
	}
}