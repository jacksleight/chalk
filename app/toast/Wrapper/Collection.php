<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Toast\Wrapper;

class Collection extends \Toast\Wrapper implements \Iterator, \Countable
{
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
		foreach ($this as $key => $get) {
			if (in_array($get->getObject(), $history, true)) {
				continue;
			}
			$array[$key] = $get->_traverse($function, $history);
		}

		return $array;
	}

	public function graphFromArray(array $array)
	{
		if ($this->_isIdList($array)) {
			$array = $this->_mapIdList($array);
			foreach ($this->_object->toArray() as $key => $value) {
				if (!in_array($value, $array, true)) {
					$this->_object->removeElement($value);
					if (isset($this->_md['inverse'])) {
						$inverse = $this->_md['inverse'];
						if ($this->_md['type'] == 'manyToMany') {
							$value->{$inverse}->removeElement($this->_object);
						} else {
							$value->{$inverse} = null;
						}
					}
				}
			}
			foreach ($array as $key => $value) {
				if (!$this->contains($value)) {
					$this->add($value);
				}
			}
			return $this;
		}

		foreach ($array as $key => $value) {
			if (is_array($value)) {
				if ($this->__isset($key)) {
					$this->__get($key)->graphFromArray($value);
				}
			} else {
				$this->__set($key, $value);
			}		
		}

		return $this;
	}

	protected function _isIdList(array $array)
	{
		foreach ($array as $value) {
			if (!is_scalar($value)) {
				return false;
			}
		}
		return true;
	}

	protected function _mapIdList(array $array)
	{
		foreach ($array as $key => $value) {
			if (!is_numeric($value)) {
				unset($array[$key]);
				continue;
			}
			$array[$key] = \Toast\Wrapper::$em->getReference($this->_md['entity'], $value);
		}
		return $array;
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
	
	public function __set($key, $value)
	{
		$this->_object->set($key, $value);
	}

	public function __get($key)
	{
		$value = $this->_object->get($key);
		if ($value instanceof \Toast\Entity) {
			return new \Toast\Wrapper\Entity($value, $this->_allowed, $this, $key);
		} else {
			return $value;
		}
	}

	public function __unset($key)
	{
		$this->_object->remove($key);
	}

	public function __isset($key)
	{
		return $this->_object->containsKey($key);
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