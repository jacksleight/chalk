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
        $rootClass = $meta->rootEntityName;
        try {
            $type = Ayre::type($class);
        } catch (\Exception $e) {
            return;
        }
        
        if ($class == $rootClass || $meta->inheritanceType == 2) {
            $meta->setTableName($type->name);
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
                $mapping['columnName'] = "{$type->name}_" . trim($mapping['columnName'], '`');
                $meta->setAttributeOverride($name, $mapping);
            }
        }
 
        $repositoryClasses = [
            $type->module->class . '\\Repository\\' . $type->entity->class,
            $type->module->class . '\\Repository\\' . Ayre::type($rootClass)->entity->class,
            'Ayre\\Repository\\' . Ayre::type($rootClass)->entity->class,
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
            foreach ($meta->discriminatorMap as $discriminatorId => $discriminatorClass) {
                unset($meta->discriminatorMap[$discriminatorId]);
                $meta->discriminatorMap[Ayre::type($discriminatorClass)->name] = $discriminatorClass;
            }
        }

        $this->_injectMetadata($class, $meta);
    }

    protected function _injectMetadata($class, $meta)
    {
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
                'type'     => $mapping['type'],
                'entity'   => $mapping['targetEntity'],
                'nullable' => isset($mapping['joinColumns'][0]['nullable']) ? $mapping['joinColumns'][0]['nullable'] : false,
            ];
        }
        $class::injectMetadata($md);
    }
}