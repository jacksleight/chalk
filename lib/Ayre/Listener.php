<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

use Ayre,
    Doctrine\ORM\Event\LoadClassMetadataEventArgs,
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
        $meta      = $args->getClassMetadata();
        $class     = $meta->name;
        $namespace = __NAMESPACE__ . '\\Entity';
        if (!is_subclass_of($class, $namespace)) {
            return;
        }

        $type = Ayre::resolve($class);
        $meta->setTableName('core_' . $type->id);

        $repositoryClasses = [
            'Ayre\\Repository\\' . $type->short,
            'Ayre\\Repository\\' . Ayre::resolve($meta->rootEntityName)->short,
            'Ayre\\Repository',
        ];
        foreach ($repositoryClasses as $repositoryClass) {
            if (class_exists($repositoryClass)) {
                $meta->setCustomRepositoryClass($repositoryClass);
                break;
            }
        }

        if (isset($meta->discriminatorMap)) {
            $map = $meta->discriminatorMap;
            foreach ($map as $id => $class) {
                unset($map[$id]);
                $map[Ayre::resolve($class)->short] = $class;
            }
            $meta->setDiscriminatorMap($map);
        }
    }
}