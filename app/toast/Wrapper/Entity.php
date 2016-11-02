<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Toast\Wrapper;

use Toast\Wrapper;

class Entity extends Wrapper
{	
	public function __construct(\Toast\Entity $object, array $allowed = null, $parent = null, $reference = null)
	{
		parent::__construct($object, $allowed, $parent, $reference);
	}
	
	public function getMetadata($type = null, $name = null)
	{
		$md = parent::getMetadata($type, $name);

		switch ($type) {
			case \Toast\Entity::MD_PROPERTIES:
			case \Toast\Entity::MD_PROPERTY:
				$md = $type == \Toast\Entity::MD_PROPERTY
					? array($name => $md)
					: $md;
				foreach ($md as $key => $value) {
					$value['context'] = array_merge($this->getContext(), array($name));
					$value['contextName'] = $id = $value['context'][0] 
						. (count($value['context']) > 1
							? '[' . implode('][', array_slice($value['context'], 1)) . ']'
							: '');
					$md[$key] = $value;
				}
				$md = $type == \Toast\Entity::MD_PROPERTY
					? $md[$name]
					: $md;
			break;
		}

		return $md;
	}

	protected function _traverse(\Closure $function, array $history = array())
	{
		$history[]	= $this->_object;
		$assocs		= $this->_object->getMetadata(\Toast\Entity::MD_ASSOC_NAMES);
		
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
		$assocs	= $this->_object->getMetadata(\Toast\Entity::MD_ASSOC_NAMES);

		foreach ($array as $name => $value) {
			$object = $this->__get($name);
			if ($object instanceof \Toast\Wrapper && is_array($value)) {
				$object->graphFromArray($value);
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
		if (is_scalar($value)) {
			$value = trim($value);
			if (strlen($value) == 0) {
				$value = null;
			}
			if (isset($value)) {
				$md = $this->getMetadata(\Toast\Entity::MD_PROPERTY, $name);
				switch ($md['type']) {
					case 'boolean':
						$value = $value == 'false'
							? false
							: (bool) $value;
					break;
					case 'date':
					case 'time':
					case 'datetime':
						try {
							$value = new \DateTime($value, new \DateTimeZone(Wrapper::$timezone));
							$value->setTimezone(new \DateTimeZone('UTC'));
						} catch (\Exception $e) {}
					break;
					case 'coast_url':
						try {
							$value = new \Coast\Url($value);
						} catch (\Exception $e) {
							$value = new \Coast\Url\Invalid($value);
						}
					break;
					case 'manyToOne':
						$value = Wrapper::$em->getReference($md['entity'], $value);
					break;
				}
			}
		} else if ($name != 'filters' && is_array($value) && $this->_isBooleanList($value)) {
			$value = $this->_mapBooleanList($value);
		}
		$isEditorContent = function($value) {
			return strpos($value, 'mceNonEditable') !== false && strpos($value, 'data-chalk') !== false;
		};
		if (isset($value)) {
			if (is_scalar($value)) {
				if ($isEditorContent($value)) {
					$value = Wrapper::$chalk->backend->parser->reverse($value);
				}
			} else if (is_array($value)) {
				array_walk_recursive($value, function(&$value) use ($isEditorContent) {
					if (isset($value) && is_scalar($value)) {
						if ($isEditorContent($value)) {
							$value = Wrapper::$chalk->backend->parser->reverse($value);
						}
					}
				});
			}
		}
		$this->_object->__set($name, $value);
	}

	public function __get($name)
	{
		$allowed = isset($this->_allowed[$name])
			? $this->_allowed[$name]
			: null;
		$value = $this->_object->__get($name);
		if ($value instanceof \Toast\Entity) {
			return new \Toast\Wrapper\Entity($value, $allowed, $this, $name);
		} elseif ($value instanceof \Doctrine\Common\Collections\Collection) {
			return new \Toast\Wrapper\Collection($value, $allowed, $this, $name, $this->getMetadata(\Toast\Entity::MD_PROPERTY, $name));
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