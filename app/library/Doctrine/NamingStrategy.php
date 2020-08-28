<?php 
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Doctrine;

use Chalk\Chalk,
    Doctrine\ORM\Mapping\NamingStrategy as DoctrineNamingStrategy;

class NamingStrategy implements DoctrineNamingStrategy
{
    public function classToTableName($class)
    {
        try {
            return Chalk::info($class)->name;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function propertyToColumnName($property, $class = null)
    {
        $column = $property;
        if (is_subclass_of($class, 'Chalk\Core\Content')) {
            $column = Chalk::info($class)->name . '_' . $column;
        }
        return $column;
    }

    public function embeddedFieldToColumnName($propertyName, $embeddedColumnName, $className = null, $embeddedClassName = null)
    {
        return $propertyName . '_' . $embeddedColumnName;
    }

    public function referenceColumnName()
    {
        return 'id';
    }

    public function joinColumnName($property, $class = null)
    {
        $column = $this->propertyToColumnName($property) . ucfirst($this->referenceColumnName());
        if (is_subclass_of($class, 'Chalk\Core\Content')) {
            $column = Chalk::info($class)->name . '_' . $column;
        }
        return $column;
    }

    public function joinTableName($source, $target, $property = null)
    {
        return $this->classToTableName($source) . '__' . $this->classToTableName($target);
    }
    
    public function joinKeyColumnName($class, $column = null)
    {
        return $this->classToTableName($class) . ucfirst($column ?: $this->referenceColumnName());
    }
}