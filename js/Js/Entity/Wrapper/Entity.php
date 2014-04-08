<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Entity\Wrapper;

class Entity extends \Js\Entity\Wrapper
{	
	public function __construct(\Js\Entity $object, array $allowed = null, $parent = null, $reference = null)
	{
		parent::__construct($object, $allowed, $parent, $reference);
	}
	
	public function getMetadata($type = null, $name = null)
	{
		$md = parent::getMetadata($type, $name);

		switch ($type) {
			case \Js\Entity::MD_PROPERTIES:
			case \Js\Entity::MD_PROPERTY:
				$md = $type == \Js\Entity::MD_PROPERTY
					? array($name => $md)
					: $md;
				foreach ($md as $key => $value) {
					$value['context'] = array_merge($this->getContext(), array($name));
					$md[$key] = $value;
				}
				$md = $type == \Js\Entity::MD_PROPERTY
					? $md[$name]
					: $md;
			break;
		}

		return $md;
	}

	protected function _traverse(\Closure $function, array $history = array())
	{
		$history[]	= $this->_object;
		$assocs		= $this->_object->getMetadata(\Js\Entity::MD_ASSOC_NAMES);
		
		$array = $function($this->_object);
		foreach ($assocs as $name) {
			if (!$this->__isset($name)) {
				continue;
			}
			$get	= $this->__get($name);
			$object = $get->getObject();
			if (in_array($object, $history, true)
				|| ($object instanceof \Doctrine\ORM\Proxy\Proxy)
				|| ($object instanceof \Doctrine\ORM\PersistentCollection && !$object->isInitialized())) {
				continue;
			}
			$array[$name] = $get->_traverse($function, $history);
		}

		return $array;
	}
	
	public function graphFromArray(array $array)
	{
		$assocs	= $this->_object->getMetadata(\Js\Entity::MD_ASSOC_NAMES);

		foreach ($array as $name => $value) {
			if (in_array($name, $assocs) && is_array($value)) {
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
				$values[] = $value;
			}
		}
		return $values;
	}

	public function __set($name, $value)
	{
		if (isset($this->_allowed) && !in_array($name, $this->_allowed)) {
			$class = get_class($this->_object);
			throw new \Exception("Property '{$name}' is protected on entity '{$class}'");
		}
		if (!$this->hasProperty($name)) {
			return;
		}
		if (is_string($value)) {
			$md = $this->getMetadata(\Js\Entity::MD_PROPERTY, $name);
			switch ($md['type']) {
				case 'boolean':
					$value = (bool) $value;
				break;
				case 'date':
				case 'time':
				case 'datetime':
					try {
						$value = new \DateTime($value);
					} catch (\Exception $e) {
						$value = new \Js\DateTime\Invalid($value);
					}
				break;
				case 'url':
					try {
						$value = new \Coast\Url($value);
					} catch (\Exception $e) {
						$value = new \Coast\Url\Invalid($value);
					}
				break;
				case 'manyToOne':
					$value = \App::$helper->em->getReference($md['entity'], $value);
				break;
			}
		} else if (is_array($value) && $this->_isBooleanList($value)) {
			$value = $this->_mapBooleanList($value);
		}
		$this->_object->__set($name, $value);
	}

	public function __get($name)
	{
		$allowed = isset($this->_allowed[$name])
			? $this->_allowed[$name]
			: null;
		$value = $this->_object->__get($name);
		if ($value instanceof \Js\Entity) {
			return new \Js\Entity\Wrapper\Entity($value, $allowed, $this, $name);
		} elseif ($value instanceof \Doctrine\Common\Collections\Collection) {
			return new \Js\Entity\Wrapper\Collection($value, $allowed, $this, $name, $this->getMetadata(\Js\Entity::MD_PROPERTY, $name));
		} else {
			return $value;
		}
	}

	public function __unset($name)
	{
		$this->_object->__unset($name);
	}

	public function __isset($name)
	{
		return $this->_object->__isset($name);
	}

	public function __toString()
	{
		return $this->_object->__toString();
	}
}