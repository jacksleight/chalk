<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Behaviour\Versionable;

use Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Events,
    Doctrine\ORM\Event\LoadClassMetadataEventArgs;

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
        
        if (!is_subclass_of($class, 'Ayre\Behaviour\Versionable') || $class != $rootClass) {
            return;
        }

        $meta->mapManyToOne([
            'fieldName'    => 'master',
            'targetEntity' => $class,
            'inversedBy'   => 'versions',
            'joinColumns'  => [[
                'name'                 => 'master_id',
                'referencedColumnName' => 'id',
                'onDelete'             => 'CASCADE',
            ]]
        ]);
        $meta->mapOneToOne([
            'fieldName'    => 'previous',
            'targetEntity' => $class,
            'inversedBy'   => 'next',
            'joinColumns'  => [[
                'name'                 => 'previous_id',
                'referencedColumnName' => 'id',
                'onDelete'             => 'CASCADE',
            ]]
        ]);
        $meta->mapOneToOne([
            'fieldName'    => 'next',
            'targetEntity' => $class,
            'inversedBy'   => 'previous',
            'joinColumns'  => [[
                'name'                 => 'next_id',
                'referencedColumnName' => 'id',
                'onDelete'             => 'CASCADE',
            ]]
        ]);
        $meta->mapOneToMany([
            'fieldName'    => 'versions',
            'targetEntity' => $class,
            'mappedBy'     => 'master',
        ]);
    }
}