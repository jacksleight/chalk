<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Toast;

use Coast\Model;
use Respect\Validation\Validator;

class Entity extends Model
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

	protected $_validated	= false;
	protected $_errors		= array();

	public static function injectMetadata(array $md)
	{
		$class = get_called_class();
		self::$_md[$class] = static::_parseMetadata(\Coast\array_merge_smart($md, $class::_defineMetadata($class)));
		return self::$_md[$class];
	}

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
			'fields'		=> array(),
			'associations'	=> array(),
			'properties'	=> array(),
		), $md);

		foreach ($md['fields'] as $name => $field) {
			$field = \Coast\array_merge_smart(array(
				'type'		=> null,
				'length'	=> null,
				'nullable'	=> false,
				'validator'	=> new Validator(),
			), $field);
			$validator = new Validator();
			$validator->not(Validator::nullValue());
			if ($field['type'] == 'integer' || $field['type'] == 'smallint' || $field['type'] == 'bigint') {
				$validator->int();
			} elseif ($field['type'] == 'decimal') {
				$validator->float();
			} elseif ($field['type'] == 'boolean') {
				$validator->bool();
			} elseif ($field['type'] == 'string') {
				$validator->string();
				if (isset($field['length'])) {
					$validator->length(null, $field['length']);
				}
			} elseif ($field['type'] == 'date' || $field['type'] == 'time' || $field['type'] == 'datetime') {
				$validator->date();
			}
			$validator->addRules($field['validator']->getRules());
			$field['validator']			= $validator;
			$md['fields'][$name]		= $field;
			$md['properties'][$name]	= $md['fields'][$name];
		}

		foreach ($md['associations'] as $name => $assoc) {
			$assoc = \Coast\array_merge_smart(array(
				'type'		=> null,
				'entity'	=> null,
				'nullable'	=> false,
				'validator'	=> new Validator(),
			), $assoc);
			$validator = new Validator();
			if (!$assoc['nullable'] && $assoc['type'] == 'manyToOne') {
				$validator->notEmpty();
			}
			$validator->addRules($assoc['validator']->getRules());
			$assoc['validator'] = $validator;

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

	protected function _validate()
	{
		$this->_validated = false;
		$this->_errors = array();

		$this->_preValidate();
		
		$properties = $this->getMetadata(self::MD_PROPERTIES);
		foreach ($properties as $name => $property) {
			$validator = $property['validator'];
			try {
				if (!$property['nullable'] || isset($this->{$name})) {
					$validator->check($this->{$name});
				}
			} catch(\Exception $e) {
				$this->addError($name, $e->getMainMessage());
			}
		}

		$this->_postValidate();

		$this->_validated = true;
	}

	protected function _preValidate()
	{}

	protected function _postValidate()
	{}

	public function addError($name, $code, array $params = array())
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

	public function isNew()
	{
		return !isset($this->id);
	}
}