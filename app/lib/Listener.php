<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
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
        $entity    = Chalk::entity($class);
        
        if ($class == $rootClass || $meta->inheritanceType == 2) {
            $meta->setTableName($entity->name);
        }

        $names = $meta->getAssociationNames();
        foreach ($names as $name) {
            $mapping = $meta->getAssociationMapping($name);
            if (!isset($mapping['joinColumns'])) {
                continue;
            }
            foreach ($mapping['joinColumns'] as $i => $joinColumn) {
                $mapping['joinColumns'][$i]['name'] = "{$mapping['fieldName']}Id";
            }
            $meta->setAssociationOverride($name, $mapping);
        }

        if ($class != $rootClass) {
            $names = $meta->getFieldNames();
            foreach ($names as $name) {
                $mapping = $meta->getFieldMapping($name);
                if (isset($mapping['inherited'])) {
                    continue;
                }
                $mapping['columnName'] = "{$entity->name}_" . trim($mapping['columnName'], '`');
                $meta->setAttributeOverride($name, $mapping);
            }
            $names = $meta->getAssociationNames();
            foreach ($names as $name) {
                $mapping = $meta->getAssociationMapping($name);
                if (isset($mapping['inherited']) || !isset($mapping['joinColumns'])) {
                    continue;
                }
                foreach ($mapping['joinColumns'] as $i => $joinColumn) {
                    $mapping['joinColumns'][$i]['name'] = "{$entity->name}_" . trim($joinColumn['name'], '`');
                }
                $meta->setAssociationOverride($name, $mapping);
            }
        }
 
        $repositoryClasses = [
            $entity->module->class . '\\Repository\\' . $entity->local->class,
            $entity->module->class . '\\Repository\\' . Chalk::entity($rootClass)->local->class,
            Chalk::entity($rootClass)->module->class . '\\Repository\\' . Chalk::entity($rootClass)->local->class,
            'Chalk\\Repository',
        ];        
        foreach ($repositoryClasses as $repositoryClass) {
            if (class_exists($repositoryClass)) {
                $meta->setCustomRepositoryClass($repositoryClass);
                break;
            }
        }

        if ($meta->discriminatorMap) {
            $meta->discriminatorMap = [
                Chalk::entity($rootClass)->name => $rootClass,
            ];
            $allClasses = $em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();
            foreach ($allClasses as $allClass) {
                if (is_subclass_of($allClass, $rootClass)) {
                    $meta->discriminatorMap[Chalk::entity($allClass)->name] = $allClass;
                }
                if (is_subclass_of($allClass, $class) && !in_array($allClass, $meta->subClasses)) {
                    $meta->subClasses[] = $allClass;
                }
            }
        }

        $this->_injectMetadata($class, $meta);
    }

    protected function _injectMetadata($class, $meta)
    {
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
            ];
        }
        $class::injectMetadata($md);
    }
}