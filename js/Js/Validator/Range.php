<?php
/* 
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Validator;

class Range extends \Js\Validator
{
	const LOW	= 'validator_range_low';
	const HIGH	= 'validator_range_high';

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
		if (isset($min) && isset($this->_max) && $min >= $this->_max) {
			throw new \Js\Validator\Exception("Minimum value must be less than maximum value");
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
		if (isset($max) && isset($this->_min) && $max <= $this->_min) {
			throw new \Js\Validator\Exception("Maximum value must be greater than minimum value");
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

		$value = (float) $value;
		if (isset($this->_min) && $value < $this->_min) {
			$this->_addError(self::LOW);
			return false;
		}
		if (isset($this->_max) && $value > $this->_max) {
			$this->_addError(self::HIGH);
			return false;
		}
		return true;
	}
}