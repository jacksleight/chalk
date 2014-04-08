<?php
/* 
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Validator;

class Chain extends \Js\Validator
{
	protected $_validators = array();

	public function __construct(array $validators = array())
	{
		$this->addValidators($validators);
	}

	public function addValidator(\Js\Validator $validator, $append = false)
	{
		if ($append) {
			array_unshift($this->_validators, $validator);
		} else {
			array_push($this->_validators, $validator);
		}
		return $this;
	}

	public function addValidators(array $validators, $append = false)
	{
		if ($append) {
			$validators = array_reverse($validators);
		}
		foreach ($validators as $validator) {
			$this->addValidator($validator, $append);
		}
		return $this;
	}

	public function removeValidator($class)
	{
		$key = $this->_findValidator($class);
		if ($key !== false) {
			unset($this->_validators[$key]);
		}		
		return $this;
	}

	public function hasValidator($class)
	{
		$key = $this->_findValidator($class);
		return ($key !== false);
	}

	public function getValidator($class)
	{
		$key = $this->_findValidator($class);
		if ($key !== false) {
			return $this->_validators[$key];
		}
		return false;
	}

	protected function _findValidator($class)
	{
		return array_search($class, array_map('get_class', $this->_validators));
	}

	public function isValid($value)
	{
		$this->_resetErrors();

		foreach ($this->_validators as $validator) {
			$break = $validator->getBreak();
			$this->setBreak($break);
			if (!$validator->isValid($value)) {
				$this->_errors = array_merge(
					$this->_errors,
					$validator->getErrors()
				);
				if ($break) {
					break;
				}
			}
		}
		return !$this->hasErrors();
	}

	public function __clone()
	{
		foreach ($this->_validators as $key => $validator) {
			$this->_validators[$key] = clone $validator;
		}
	}
}