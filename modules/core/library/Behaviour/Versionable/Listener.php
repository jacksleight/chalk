<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Behaviour\Versionable;

use Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Events,
    Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class Listener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        // return [
        //     Events::loadClassMetadata,
        // ];
    }

    // public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    // {
    //     $meta      = $args->getClassMetadata();
    //     $class     = $meta->name;
    //     $rootClass = $meta->rootEntityName;
        
    //     if (!is_subclass_of($class, 'Chalk\Core\Behaviour\Versionable') || $class != $rootClass) {
    //         return;
    //     }

    //     $meta->mapManyToOne([
    //         'fieldName'    => 'master',
    //         'targetEntity' => $class,
    //         'inversedBy'   => 'versions',
    //         'joinColumns'  => [[
    //             'name'                 => 'masterId',
    //             'referencedColumnName' => 'id',
    //             'onDelete'             => 'CASCADE',
    //         ]]
    //     ]);
    //     $meta->mapOneToOne([
    //         'fieldName'    => 'previous',
    //         'targetEntity' => $class,
    //         'joinColumns'  => [[
    //             'name'                 => 'previousId',
    //             'referencedColumnName' => 'id',
    //             'onDelete'             => 'CASCADE',
    //         ]]
    //     ]);
    //     $meta->mapOneToOne([
    //         'fieldName'    => 'next',
    //         'targetEntity' => $class,
    //         'joinColumns'  => [[
    //             'name'                 => 'nextId',
    //             'referencedColumnName' => 'id',
    //             'onDelete'             => 'CASCADE',
    //         ]]
    //     ]);
    //     $meta->mapOneToMany([
    //         'fieldName'    => 'versions',
    //         'targetEntity' => $class,
    //         'mappedBy'     => 'master',
    //     ]);
    // }
}