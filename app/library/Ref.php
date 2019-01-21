<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk;

use Coast;
use ArrayAccess;
use Chalk\Chalk;

class Ref implements ArrayAccess
{
    protected $_type;
    protected $_id;
    protected $_sub;
    protected $_entity;

    public function __construct($value)
    {
        if (isset($value)) {
            if (is_array($value)) {
                $this->fromArray($value);
            } else {
                $this->fromString($value);
            }
        }
    }

    public function fromString($value)
    {
        $value = array_combine(
            ['type', 'id', 'sub'],
            explode('/', $value) + [null, null, null]
        );
        if (isset($value['sub'])) {
            $value['sub'] = Chalk::sub($value['sub']);
        }
        return $this->fromArray($value);
    }

    public function toString()
    {
        $value = $this->toArray();
        if (isset($value['sub'])) {
            $value['sub'] = Chalk::sub($value['sub'], true);
        }
        return implode('/', Coast\array_filter_null($value));
    }

    public function fromArray(array $parts)
    {
        $parts = array_intersect_key($parts, array_flip([
            'type',
            'id',
            'sub',
        ]));
        foreach ($parts as $method => $value) {
            $this->{$method}($value);
        }
        return $this;
    }

    public function toArray()
    {
        return [
            'type' => $this->type(),
            'id'   => $this->id(),
            'sub'  => $this->sub(),
        ];
    }
    
    public function type($type = null)
    {
        if (func_num_args() > 0) {
            $this->_type = $type;
            return $this;
        }
        return $this->_type;
    }
    
    public function id($id = null)
    {
        if (func_num_args() > 0) {
            $this->_id = $id;
            return $this;
        }
        return $this->_id;
    }
    
    public function sub($sub = null)
    {
        if (func_num_args() > 0) {
            $this->_sub = $sub;
            return $this;
        }
        return $this->_sub;
    }
    
    public function entity($entity = null)
    {
        if (func_num_args() > 0) {
            $this->_entity = $entity;
            return $this;
        }
        return $this->_entity;
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function __get($name)
    {
        if ($name[0] == '_') {
            throw new \Exception("Access to '{$name}' is prohibited");
        }
        if (method_exists($this, $name)) {
            return $this->{$name}();
        } else if (property_exists($this, $name)) {
            return $this->{$name};
        } else {
            throw new \Exception("Property '{$name}' does not exist");
        }
    }

    public function offsetSet($offset, $value)
    {
       
    }

    public function offsetExists($offset)
    {
        
    }

    public function offsetUnset($offset)
    {
        
    }

    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }
}