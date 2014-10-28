<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Toast\Wrapper;

class Collection extends \Toast\Wrapper implements \Iterator, \Countable
{
	public static $em;

	protected $_md;
	protected $_iterator;

	public function __construct($object, array $allowed = null, $parent = null, $reference = null, array $md = null)
	{
		if (is_array($object)) {
			$object = new \Doctrine\Common\Collections\ArrayCollection($object);
		}
		parent::__construct($object, $allowed, $parent, $reference);

		$this->_md = $md;
	}

	protected function _traverse(\Closure $function, array $history = array())
	{
		$history[] = $this->_object;

		$array = array();
		foreach ($this as $name => $get) {
			if (in_array($get->getObject(), $history, true)) {
				continue;
			}
			$array[$name] = $get->_traverse($function, $history);
		}

		return $array;
	}

	public function graphFromArray(array $array)
	{
		if ($this->_isBooleanList($array)) {
			$array = $this->_mapBooleanList($array);
			foreach ($this->toArray() as $name => $value) {
				if (!isset($array[$name])) {
					$inverse = $this->_md['inverse'];
					$this->_object->removeElement($value);
					if ($this->_md['type'] == 'manyToMany') {
						$value->{$inverse}->removeElement($this->_object);
					} else {
						$value->{$inverse} = null;
					}
				}
			}
			foreach ($array as $name => $value) {
				if (!$this->contains($value)) {
					$this->add($value);
				}
			}
			return $this;
		}

		foreach ($array as $name => $value) {
			if (is_array($value)) {
				if (!$this->__isset($name)) {
				//	$class = $this->_md['entity'];
				//	$this->__set($name, new $class());
					continue;
				}
				$this->__get($name)->graphFromArray($value);
			} else {
				$this->__set($name, $value);
			}		
		}

		return $this;
	}

	protected function _mapBooleanList(array $array)
	{
		$values = array();
		foreach ($array as $value => $bool) {
			if ($bool) {
				$values[$value] = self::$em->getReference($this->_md['entity'], $value);
			}
		}
		return $values;
	}

	public function first()
	{
		$this->_object->first();
		return $this->__get($this->_object->key());
	}

	public function last()
	{
		$this->_object->last();
		return $this->__get($this->_object->key());
	}
	
	public function __set($name, $value)
	{
		$this->_object->set($name, $value);
	}

	public function __get($name)
	{
		$value = $this->_object->get($name);
		if ($value instanceof \Toast\Entity) {
			return new \Toast\Wrapper\Entity($value, $this->_allowed, $this, $name);
		} else {
			return $value;
		}
	}

	public function __unset($name)
	{
		$this->_object->remove($name);
	}

	public function __isset($name)
	{
		return $this->_object->containsKey($name);
	}

	public function rewind()
	{
		return $this->_object->first();
	}

	public function current()
	{
		return new \Toast\Wrapper\Entity($this->_object->current(), $this->_allowed, $this, $this->key());
	}

	public function key()
	{
		return $this->_object->key();
	}

	public function next()
	{
		return $this->_object->next();
	}

	public function valid()
	{
		return $this->_object->containsKey($this->key());
	}

	public function count()
	{
		return $this->_object->count();
	}
}