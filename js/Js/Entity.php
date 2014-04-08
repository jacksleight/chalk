<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js;

abstract class Entity implements \ArrayAccess
{
	const MD_TABLE			= 'MD_TABLE';
	const MD_INHERITANCE	= 'MD_INHERITANCE';
	const MD_FIELD_NAMES	= 'MD_FIELD_NAMES';
	const MD_FIELDS			= 'MD_FIELDS';
	const MD_ASSOC_NAMES	= 'MD_ASSOC_NAMES';
	const MD_ASSOCS			= 'MD_ASSOCS';
	const MD_PROPERTY_NAMES	= 'MD_PROPERTY_NAMES';
	const MD_PROPERTIES		= 'MD_PROPERTIES';
	const MD_PROPERTY		= 'MD_PROPERTY';

	protected static $_md;

	protected $_validated = false;
	protected $_errors = array();

	protected static function _getMetadata()
	{
		$class = get_called_class();
		if (!isset(self::$_md[$class])) {
			self::$_md[$class] = static::_parseMetadata($class::_defineMetadata($class));
		}
		return self::$_md[$class];
	}

	protected static function _parseMetadata($md)
	{	
		$md = array_merge(array(
			'table'			=> null,
			'inheritance'	=> null,
			'fields'		=> array(),
			'associations'	=> array(),
			'properties'	=> array(),
		), $md);

		foreach ($md['fields'] as $name => $field) {
			$field = \Coast\array_merge_smart(array(
				'column'	=> null,
				'persist'	=> true,
				'id'		=> false,
				'auto'		=> false,
				'type'		=> null,
				'length'	=> null,
				'values'	=> null,
				'nullable'	=> false,
				'unique'	=> false,
				'precision'	=> 0,
				'scale'		=> 0,
				'validator'	=> new \Js\Validator\Chain(),
			), $field);
			$list = array();
			if (!$field['nullable'] && !$field['auto']) {
				$list[] = new \Js\Validator\Set();
			}
			if ($field['type'] == 'integer' || $field['type'] == 'smallint' || $field['type'] == 'bigint') {
				$list[] = new \Js\Validator\Integer();
			} elseif ($field['type'] == 'decimal') {
				$list[] = new \Js\Validator\Decimal();
			} elseif ($field['type'] == 'boolean') {
				$list[] = new \Js\Validator\Boolean();
			} elseif ($field['type'] == 'string' && isset($field['length'])) {
				if ($field['validator']->hasValidator('Js\Validator\Length')) {
					$length = $field['validator']->getValidator('Js\Validator\Length');
					if ($field['length'] < $length->getMax()) {
						$length->setMax($field['length']);
					}
				} else {
					$length = new \Js\Validator\Length(null, $field['length']);
				}
				$list[] = $length;
			} elseif ($field['type'] == 'date' || $field['type'] == 'time' || $field['type'] == 'datetime') {
				$list[] = new \Js\Validator\DateTime();
			} elseif ($field['type'] == 'url') {
				$list[] = new \Js\Validator\Url();
			}
			$field['validator']->addValidators($list, true);

			$md['fields'][$name]	 = $field;
			$md['properties'][$name] = $md['fields'][$name];
		}

		foreach ($md['associations'] as $name => $assoc) {
			$assoc = \Coast\array_merge_smart(array(
				'type'		=> null,
				'table'		=> array(
					'name' => null,
					'join' => array(
						'name'		=> null,
						'onUpdate'	=> null,
						'onDelete'	=> null,
					),
					'inverseJoin' => array(
						'name'		=> null,
						'onUpdate'	=> null,
						'onDelete'	=> null,
					),
				),
				'persist'	=> true,
				'entity'	=> null,
				'inverse'	=> null,
				'owner'		=> false,
				'cascade'	=> array('persist'),
				'onUpdate'	=> null,
				'onDelete'	=> null,
				'nullable'	=> false,
				'validator'	=> new \Js\Validator\Chain(),
			), $assoc);
			$list = array();
			if (!$assoc['nullable'] && $assoc['type'] == 'manyToOne') {
				$list[] = new \Js\Validator\Set();
			}
			$assoc['validator']->addValidators($list, true);

			$md['associations'][$name]	= $assoc;
			$md['properties'][$name]	= $md['associations'][$name];
		}

		return $md;
	}

	protected static function _defineMetadata($class)
	{
		return array();
	}
	
	public function __construct()
	{	
		$assocs = $this->getMetadata(self::MD_ASSOCS);
		foreach ($assocs as $name => $assoc) {
			if ($assoc['type'] == 'oneToMany' || $assoc['type'] == 'manyToMany') {
				$this->{$name} = new \Doctrine\Common\Collections\ArrayCollection();
			}
		}
	}

	public function hasProperty($name)
	{
		$md = self::_getMetadata();
		return isset($md['properties'][$name]);
	}

	public function getMetadata($type = null, $name = null)
	{
		$md = self::_getMetadata();

		switch ($type) {
			case self::MD_TABLE:
				$md = $md['table'];
			break;
			case self::MD_INHERITANCE:
				$md = $md['inheritance'];
			break;
			case self::MD_FIELD_NAMES:
				$md = array_keys($md['fields']);
			break;
			case self::MD_FIELDS:
				$md = $md['fields'];
			break;
			case self::MD_ASSOC_NAMES:
				$md = array_keys($md['associations']);
			break;
			case self::MD_ASSOCS:
				$md = $md['associations'];
			break;
			case self::MD_PROPERTY_NAMES:
				$md = array_keys($md['properties']);
			break;
			case self::MD_PROPERTIES:
			case self::MD_PROPERTY:
				$md = $type == self::MD_PROPERTY
					? array($name => $md['properties'][$name])
					: $md['properties'];
				foreach ($md as $key => $value) {
					$value['name'] = $key;
					$value['validator'] = clone $value['validator'];
					$method = '_alterMetadata';
					$value = method_exists($this, $method)
						? $this->{$method}($key, $value)
						: $value;
					$method = '_alter' . ucfirst($key) . 'Metadata';
					$value = method_exists($this, $method)
						? $this->{$method}($value)
						: $value;
					$md[$key] = $value;
				}
				$md = $type == self::MD_PROPERTY
					? $md[$name]
					: $md;
			break;
		}

		return $md;
	}

	public function toArray()
	{
		$array = array();
		foreach ($this->getMetadata(self::MD_PROPERTY_NAMES) as $name) {
			$array[$name] = $this->__get($name);
		}
		return $array;
	}

	public function fromArray(array $array)
	{
		foreach ($array as $name => $value) {
			$this->__set($name, $value);
		}
		return $this;
	}

	public function __get($name)
	{
		$method = 'get' . ucfirst($name);
		if (method_exists($this, $method)) {
			$value = $this->{$method}();
		} else {
			$value = $this->{$name};
		}
		return $value;
	}

	public function __set($name, $value)
	{
		$method = 'set' . ucfirst($name);
		if (method_exists($this, $method)) {
			$this->{$method}($value);
		} else {
			$this->{$name} = $value;
		}
		$this->_validated = false;
		$this->_errors = array();
		return $this;
	}

	public function __unset($name)
	{
		unset($this->{$name});
		return $this;
	}

	public function __isset($name)
	{
		return isset($this->{$name});
	}

	protected function _validate()
	{
		$this->_validated = false;
		$this->_errors = array();

		$this->_preValidate();
		
		$properties = $this->getMetadata(self::MD_PROPERTIES);
		foreach ($properties as $name => $property) {
			$validator = $property['validator'];
			if (!$validator->isValid($this->{$name})) {
				foreach ($validator->getErrors() as $code => $params) {
					$this->_addError($name, $code, $params);
				}
			}
		}

		$this->_postValidate();

		$this->_validated = true;
	}

	protected function _preValidate()
	{}

	protected function _postValidate()
	{}

	protected function _addError($name, $code, array $params = array())
	{
		if (!isset($this->_errors[$name])) {
			$this->_errors[$name] = array();
		}
		return $this->_errors[$name][$code] = $params;
	}

	public function isValid(array $names = null)
	{
		if (!$this->_validated) {
			$this->_validate();
		}
		return !$this->hasErrors($names);
	}

	public function hasErrors(array $names = null)
	{
		return isset($names)
			? count(\Coast\array_intersect_key($this->_errors, $names)) > 0
			: count($this->_errors) > 0;
	}

	public function getErrors(array $names = null)
	{
		return isset($names)
			? \Coast\array_intersect_key($this->_errors, $names)
			: $this->_errors;
	}

	public function offsetSet($name, $value)
	{
		return $this->__set($name, $value);
	}

	public function offsetGet($name)
	{
		return $this->__get($name);
	}

	public function offsetUnset($name)
	{
		return $this->__unset($name);
	}

	public function offsetExists($name)
	{
		return $this->__isset($name);
	}

	public function __toString()
	{
		return (string) $this->id;
	}
}