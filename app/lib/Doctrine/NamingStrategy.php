<?php 
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Doctrine;

use Chalk\Chalk,
    Doctrine\ORM\Mapping\NamingStrategy as DoctrineNamingStrategy;

class NamingStrategy implements DoctrineNamingStrategy
{
    public function classToTableName($class)
    {
        return Chalk::info($class)->name;
    }

    public function propertyToColumnName($property, $class = null)
    {
        $column = $property;
        if (is_subclass_of($class, 'Chalk\Core\Content')) {
            $column = Chalk::info($class)->name . '_' . $column;
        }
        return $column;
    }

    public function referenceColumnName()
    {
        return 'id';
    }

    public function joinColumnName($property, $class = null)
    {
        $column = $this->propertyToColumnName($property) . ucfirst($this->referenceColumnName());
        // @hack Not currenty supported doe to Doctrine limitation
        // if (is_subclass_of($class, 'Chalk\Core\Content')) {
        //     $column = Chalk::info($class)->name . '_' . $column;
        // }
        return $column;
    }

    public function joinTableName($source, $target, $property = null)
    {
        return $this->classToTableName($source) . '_' . $this->classToTableName($target);
    }
    
    public function joinKeyColumnName($class, $column = null)
    {
        return $this->classToTableName($class) . '_' . ($column ?: $this->referenceColumnName());
    }
}