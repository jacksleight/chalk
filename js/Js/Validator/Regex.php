<?php
/* 
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Validator;

class Regex extends \Js\Validator
{
	const INVALID = 'validator_regex_invalid';

	protected $_break = true;

	protected $_properties = array('regex');
	protected $_regex;

	public function __construct($regex)
	{
		$this->_regex = $regex;
	}

	public function isValid($value)
	{
		$this->_resetErrors();
		if (!isset($value)) {
			return true;
		}

		$value = (string) $value;
		if (!preg_match($this->_regex, $value)) {
			$this->_addError(constant(get_class($this) . '::INVALID'));
			return false;
		}
		return true;
	}
}
