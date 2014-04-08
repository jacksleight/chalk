<?php
/* 
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js;

abstract class Validator
{
	protected $_break = false;
	protected $_errors = array();
	protected $_properties = array();

	protected function _resetErrors()
	{
		$this->_errors = array();
		return $this;
	}

	abstract public function isValid($value);

	protected function _addError($code)
	{
		$properties = array();
		foreach ($this->_properties as $property) {
			$name = '_' . $property;
			$properties[] = $this->{$name};
		}
		$this->_errors[$code] = $properties;
		return $this;
	}

	public function hasErrors()
	{
		return count($this->_errors) > 0;
	}

	public function getErrors()
	{
		return $this->_errors;
	}

	public function setBreak($break)
	{
		$this->_break = $break;
		return $this;
	}

	public function getBreak()
	{
		return $this->_break;
	}
}