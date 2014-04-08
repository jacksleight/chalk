<?php
/* 
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Validator;

class Length extends \Js\Validator
{
	const SHORT	= 'validator_length_short';
	const LONG	= 'validator_length_long';

	protected $_properties = array('min', 'max');
	protected $_min = null;
	protected $_max = null;

	public function __construct($min = null, $max = null)
	{
		$this->setMin($min);
		$this->setMax($max);
	}

	public function setMin($min)
	{
		if (isset($this->_max) && isset($min) && $min > $this->_max) {
			throw new \Js\Validator\Exception("Minimum length must be less than or equal to maximum length");
		}

		$this->_min = $min;
		return $this;
	}

	public function getMin()
	{
		return $this->_min;
	}

	public function setMax($max)
	{
		if (isset($this->_min) && isset($max) && $max < $this->_min) {
			throw new \Js\Validator\Exception("Maximum length must be greater than or equal to minimum length");
		}

		$this->_max = $max;
		return $this;
	}

	public function getMax()
	{
		return $this->_max;
	}

	public function isValid($value)
	{
		$this->_resetErrors();
		if (!isset($value)) {
			return true;
		}

		$value = (string) $value;
		$length = strlen($value);
		if (isset($this->_min) && $length < $this->_min) {
			$this->_addError(self::SHORT);
			return false;
		}
		if (isset($this->_max) && $length > $this->_max) {
			$this->_addError(self::LONG);
			return false;
		}
		return true;
	}
}
