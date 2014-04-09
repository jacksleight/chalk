<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre;

abstract class Wrapper implements \ArrayAccess
{
	protected $_object;
	protected $_allowed;
	protected $_parent;
	protected $_reference;

	public function __construct($object, array $allowed = null, $parent = null, $reference = null)
	{
		$this->_object		= $object;
		$this->_allowed		= $allowed;
		$this->_parent		= $parent;
		$this->_reference	= $reference;
	}

	public function getObject()
	{
		return $this->_object;
	}

	public function getParent()
	{
		return $this->_parent;
	}

	public function getContext()
	{
		return isset($this->_parent)
			? array_merge(
				$this->_parent->getContext(),
				array($this->_reference)
			) : array();
	}
	
	abstract protected function _traverse(\Closure $function, array $history = array());
	
	public function graphToArray()
	{
		return $this->_traverse(function(\Ayre\Entity $entity) {
			$names = $entity->getMetadata(\Ayre\Entity::MD_FIELD_NAMES);			
			$array = array();
			foreach ($names as $name) {
				$array[$name] = $entity->__get($name);
			}
			return $array;
		});	
	}	
		
	public function graphIsValid()
	{
		$valid = true;
		$this->_traverse(function(\Ayre\Entity $entity) use (&$valid) {
			if (!$entity->isValid()) {
				$valid = false;		
			}
		});	
		return $valid;
	}	
	
	public function graphHasErrors()
	{
		$hasErrors = false;
		$this->_traverse(function(\Ayre\Entity $entity) use (&$hasErrors) {
			if ($entity->hasErrors()) {
				$hasErrors = true;
			}
		});	
		return $hasErrors;
	}
	
	public function graphGetErrors()
	{
		return $this->_traverse(function(\Ayre\Entity $entity) {
			return $entity->getErrors();
		});	
	}
	
	abstract public function graphFromArray(array $array);

	protected function _isBooleanList(array $array)
	{
		foreach ($array as $name => $value) {
			if (!is_numeric($value) || ($value != 0 && $value != 1)) {
				return false;
			}
		}
		return true;
	}

	abstract protected function _mapBooleanList(array $array);

	public function __call($method, $args)
	{
		return call_user_func_array(array($this->_object, $method), $args);
	}

	abstract public function __set($name, $value);

	abstract public function __get($name);

	abstract public function __unset($name);

	abstract public function __isset($name);

	public function offsetSet($name, $value)
	{
		$this->__set($name, $value);
	}

	public function offsetGet($name)
	{
		return $this->__get($name);
	}

	public function offsetUnset($name)
	{
		$this->__unset($name);
	}

	public function offsetExists($name)
	{
		return $this->__isset($name);
	}
}