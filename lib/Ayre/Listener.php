<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

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
        $parts = explode('\\', $class);
        if ($parts[0] != 'Ayre') {
            return;
        }
        array_shift($parts);

        $meta->setTableName(implode('_', array_map('lcfirst', $parts)));

        $repositoryClass = "Ayre\\Repository\\" . implode('\\', $parts);
        $meta->setCustomRepositoryClass(class_exists($repositoryClass)
            ? $repositoryClass
            : (is_subclass_of($class, 'Ayre\\Silt')
                ? 'Ayre\\Repository\\Silt'
                : 'Ayre\\Repository'));

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