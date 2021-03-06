<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk;

use Chalk\Chalk,
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
        $em        = $args->getEntityManager();
        $meta      = $args->getClassMetadata();
        $class     = $meta->name;
        $rootClass = $meta->rootEntityName;
        $info      = Chalk::info($class);
        
        $parentClasses = array_merge([$class], $meta->parentClasses);
        $repositoryClasses = [];
        foreach ($parentClasses as $parentClass) {
            $parentInfo = Chalk::info($parentClass);
            $repositoryClasses[] = $parentInfo->module->class . '\\Repository\\' . $parentInfo->local->class;
        }
        $repositoryClasses[] = 'Chalk\\Repository';
        foreach ($repositoryClasses as $repositoryClass) {
            if (class_exists($repositoryClass)) {
                $meta->setCustomRepositoryClass($repositoryClass);
                break;
            }
        }

        if ($meta->discriminatorMap) {
            $meta->discriminatorMap = [
                Chalk::info($rootClass)->name => $rootClass,
            ];
            $allClasses = $em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();
            foreach ($allClasses as $allClass) {
                if (is_subclass_of($allClass, $rootClass)) {
                    $meta->discriminatorMap[Chalk::info($allClass)->name] = $allClass;
                }
                if (is_subclass_of($allClass, $class) && !in_array($allClass, $meta->subClasses)) {
                    $meta->subClasses[] = $allClass;
                }
            }
        }
    }
}