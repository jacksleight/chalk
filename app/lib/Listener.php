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
        $info      = Chalk::info($class);
        
        if ($class == $rootClass || $meta->inheritanceType == 2) {
            $meta->setTableName($info->name);
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
                $mapping['columnName'] = "{$info->name}_" . trim($mapping['columnName'], '`');
                $meta->setAttributeOverride($name, $mapping);
            }
            $names = $meta->getAssociationNames();
            foreach ($names as $name) {
                $mapping = $meta->getAssociationMapping($name);
                if (isset($mapping['inherited']) || !isset($mapping['joinColumns'])) {
                    continue;
                }
                foreach ($mapping['joinColumns'] as $i => $joinColumn) {
                    $mapping['joinColumns'][$i]['name'] = "{$info->name}_" . trim($joinColumn['name'], '`');
                }
                $meta->setAssociationOverride($name, $mapping);
            }
        }
 
        $repositoryClasses = [
            $info->module->class . '\\Repository\\' . $info->local->class,
            $info->module->class . '\\Repository\\' . Chalk::info($rootClass)->local->class,
            Chalk::info($rootClass)->module->class . '\\Repository\\' . Chalk::info($rootClass)->local->class,
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