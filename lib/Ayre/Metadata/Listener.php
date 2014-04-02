<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Metadata;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs,
    Doctrine\ORM\Events,
    Doctrine\Common\EventSubscriber;

class Listener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $meta  = $args->getClassMetadata();
        $class = $meta->name;
        
        $parts = explode('\\', str_replace('Ayre\\', null, $class));
        foreach ($parts as $i => $name) {
            $parts[$i] = lcfirst($name);
        }
        $meta->setTableName(implode('_', $parts));

        $repositoryClass = "{$class}\Repository";
        if (class_exists($repositoryClass)) {
            $meta->setCustomRepositoryClass($repositoryClass);
        }

        if (isset($meta->discriminatorMap)) {
            $map = $meta->discriminatorMap;
            foreach ($map as $id => $class) {
                unset($map[$id]);
                $map[$class] = $class;
            }
            $meta->setDiscriminatorMap($map);
        }
    }
}