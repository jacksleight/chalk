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
        try {
            $type = Ayre::type($class);
        } catch (\Exception $e) {
            return;
        }
        
        $meta->setTableName($type->type);

        $repositoryClasses = [
            $type->module->class . '\\Repository\\' . $type->local->class,
            $type->module->class . '\\Repository\\' . Ayre::type($meta->rootEntityName)->local->class,
            'Ayre\\Repository\\' . Ayre::type($meta->rootEntityName)->local->class,
            $type->module->class . '\\Repository',
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
                $map[Ayre::type($class)->type] = $class;
            }
            $meta->setDiscriminatorMap($map);
        }
    }
}