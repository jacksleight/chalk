<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Doctrine\Event;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class LoadClassMetadata
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $class = $classMetadata->getName();
        
        $parts = explode('\\', $class);
        array_shift($parts);
        foreach ($parts as $i => $name) {
            $parts[$i] = lcfirst($name);
        }
        $classMetadata->setTableName(implode('_', $parts));

        $repositoryClass = "{$class}\Repository";
        if (class_exists($repositoryClass)) {
            $classMetadata->setCustomRepositoryClass($repositoryClass);
        }
    }
}