<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Validator;

class Words extends \Js\Validator
{
	const LOW	= 'validator_words_low';
	const HIGH	= 'validator_words_high';

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
		if (isset($this->_max) && isset($min) && $min >= $this->_max) {
			throw new \Js\Validator\Exception("Minimum words must be less than maximum words");
		}

		$this->_min = $min;
		return $this;
	}

	public function setMax($max)
	{
		if (isset($this->_min) && isset($max) && $max <= $this->_min) {
			throw new \Js\Validator\Exception("Maximum words must be greater than minimum words");
		}

		$this->_max = $max;
		return $this;
	}

	public function isValid($value)
	{
		$this->_resetErrors();
		if (!isset($value)) {
			return true;
		}

		$value = strip_tags($value);
		$words = preg_split('/\s+/', $value);
		if (isset($this->_min) && count($words) < $this->_min) {
			$this->_addError(self::LOW);
			return false;
		}
		if (isset($this->_max) && count($words) > $this->_max) {
			$this->_addError(self::HIGH);
			return false;
		}
		return true;
	}
}