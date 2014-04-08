<?php
/* 
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Validator;

class Password extends \Js\Validator
{
	const FEW_DIGIT		= 'validator_password_fewDigit';
	const FEW_UPPERCASE	= 'validator_password_fewUppercase';
	const FEW_SPECIAL	= 'validator_password_fewSpecial';

	protected $_properties  = array('digit', 'uppercase', 'special');
	protected $_digit		= 0;
	protected $_uppercase	= 0;
	protected $_special		= 0;

	public function __construct($digit = 0, $uppercase = 0, $special = 0)
	{
		$this->setDigit($digit);
		$this->setUppercase($uppercase);
		$this->setSpecial($special);
	}

	public function setDigit($digit)
	{
		$this->_digit = $digit;
		return $this;
	}

	public function setUppercase($uppercase)
	{
		$this->_uppercase = $uppercase;
		return $this;
	}

	public function setSpecial($special)
	{
		$this->_special = $special;
		return $this;
	}

	public function isValid($value)
	{
		$this->_resetErrors();
		if (!isset($value)) {
			return true;
		}

		preg_match_all('/[0-9]/', $value, $matches);
		if (count($matches[0]) < $this->_digit) {
			$this->_addError(self::FEW_DIGIT);
			return false;
		}
		preg_match_all('/[A-Z]/', $value, $matches);
		if (count($matches[0]) < $this->_uppercase) {
			$this->_addError(self::FEW_UPPERCASE);
			return false;
		}
		preg_match_all('/[^a-z0-9]/i', $value, $matches);
		if (count($matches[0]) < $this->_special) {
			$this->_addError(self::FEW_SPECIAL);
			return false;
		}
		return true;
	}
}
