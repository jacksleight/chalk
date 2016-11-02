<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Toast;

use Coast\Validator;
use Toast\Wrapper;
use ArrayAccess;

class Entity implements ArrayAccess
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

	// protected $_validated	= false;
	protected $_errors		= array();

	protected static function _fetchMetadata()
	{
		$class = get_called_class();
		try {
			$meta = Wrapper::$em->getClassMetadata($class);
		} catch(\Exception $e) {
			return [];
		}

		$types = [
            1  => 'oneToOne',
            2  => 'manyToOne',
            4  => 'oneToMany',
            8  => 'manyToMany',
            3  => 'toOne',
            12 => 'toMany',
        ];

        $md = [];
        foreach ($meta->fieldMappings as $mapping) {
            $md['fields'][$mapping['fieldName']] = [
                'id'       => isset($mapping['id']) ? $mapping['id'] : false,
                'type'     => $mapping['type'],
                'length'   => $mapping['length'],
                'nullable' => $mapping['fieldName'] == 'id' ? true : $mapping['nullable'],
            ];
        }
        foreach ($meta->associationMappings as $mapping) {
            $md['associations'][$mapping['fieldName']] = [
                'type'     => $types[$mapping['type']],
                'entity'   => $mapping['targetEntity'],
                'nullable' => isset($mapping['joinColumns'][0]['nullable']) ? $mapping['joinColumns'][0]['nullable'] : false,
                'inverse'  => $mapping['inversedBy'],
            ];
        }
        return $md;
	}

	protected static function _getMetadata()
	{
		$class = get_called_class();
		if (!isset(self::$_md[$class])) {
			self::$_md[$class] = static::_parseMetadata(\Coast\array_merge_smart($class::_fetchMetadata($class), $class::_defineMetadata($class)));
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
				'id'		=> false,
				'type'		=> null,
				'length'	=> null,
				'nullable'	=> false,
				'validator'	=> new Validator(),
			), $field);
			$validator = new Validator();
			if (!$field['id']) {
                if (!$field['nullable']) {
                    $validator->set()->break();                    
                } else {
                    $validator->break();
                }
			}
			if ($field['type'] == 'integer' || $field['type'] == 'smallint' || $field['type'] == 'bigint') {
				$validator->integer();
			} else if ($field['type'] == 'float' || $field['type'] == 'decimal') {
				$validator->float();
			} else if ($field['type'] == 'boolean') {
				$validator->boolean();
			} else if ($field['type'] == 'array' || $field['type'] == 'simple_array' || $field['type'] == 'json_array') {
				$validator->array();
			} else if ($field['type'] == 'string' || $field['type'] == 'text' || $field['type'] == 'guid') {
				$validator->string();
			} else if ($field['type'] == 'date') {
				$validator->object('DateTime');
			} else if ($field['type'] == 'time') {
				$validator->object('DateTime');
			} else if ($field['type'] == 'datetime' || $field['type'] == 'datetimez') {
				$validator->object('DateTime');
			}
			if (isset($field['length'])) {
				$validator->length(null, $field['length']);
			}
			$validator->steps($field['validator']->steps());
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
				$validator->set()->break();
			}
			$validator->steps($assoc['validator']->steps());
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
		// $this->_validated = false;
		$this->_errors = array();

		$this->_preValidate();
		
		$properties = $this->getMetadata(self::MD_PROPERTIES);
		foreach ($properties as $name => $property) {
			if ($property['nullable'] && !isset($this->{$name})) {
				continue;
			}
			$validator = $property['validator'];
			if (!$validator($this->{$name})) {
				foreach ($validator->errors() as $error) {
					$this->addError($name, $error[0] . (isset($error[1]) ? "_{$error[1]}" : null), $error[2]);
				}
			}
		}

		$this->_postValidate();

		// $this->_validated = true;
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
		// if (!$this->_validated) {
			$this->_validate();
		// }
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

    public function toArray()
    {
        $array = array();
        foreach (array_keys(get_object_vars($this)) as $name) {
            if ($name[0] == '_') {
                continue;
            }
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

    public function __set($name, $value)
    {
        if ($name[0] == '_') {
            throw new Model\Exception("Access to '{$name}' is prohibited");  
        }
        $name = \Coast\str_camel($name);
        if (method_exists($this, $name)) {
            $this->{$name}($value);
        } else if (property_exists($this, $name)) {
            $this->{$name} = $value;
        }
    }

    public function __get($name)
    {
        if ($name[0] == '_') {
            throw new Model\Exception("Access to '{$name}' is prohibited");  
        }
        $name = \Coast\str_camel($name);
        if (method_exists($this, $name)) {
            return $this->{$name}();
        } else if (property_exists($this, $name)) {
            return $this->{$name};
        } else {
            return null;
        }
    }

    public function __isset($name)
    {
        if ($name[0] == '_') {
            throw new Model\Exception("Access to '{$name}' is prohibited");  
        }
        $name = \Coast\str_camel($name);
        if (method_exists($this, $name)) {
            return $this->{$name}() !== null;
        } else if (property_exists($this, $name)) {
            return $this->{$name} !== null;
        } else {
            return false;
        }
    }

    public function __unset($name)
    {
        if ($name[0] == '_') {
            throw new Model\Exception("Access to '{$name}' is prohibited");  
        }
        $name = \Coast\str_camel($name);
        if (property_exists($this, $name)) {
            $this->{$name} = null;
        }
    }

    public function __call($name, array $args)
    {
        if ($name[0] == '_') {
            throw new Model\Exception("Access to '{$name}' is prohibited");  
        }
        $name = \Coast\str_camel($name);
        if (isset($args[0])) {
            $this->__set($name, $args[0]);
            return $this;
        }
        return $this->__get($name);
    }

    public function offsetSet($offset, $value)
    {
       return $this->__set($offset, $value);
    }

    public function offsetExists($offset)
    {
        return $this->__isset($offset);
    }

    public function offsetUnset($offset)
    {
        return $this->__unset($offset);
    }

    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }
}