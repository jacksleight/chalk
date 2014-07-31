<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Behaviour\Searchable;

use Ayre,
    Ayre\Core\Index,
    Ayre\Behaviour\Searchable,
    Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Event\OnFlushEventArgs,
    Doctrine\ORM\Event\PostFlushEventArgs,
    Doctrine\ORM\Events,
    Doctrine\ORM\UnitOfWork;

class Listener implements EventSubscriber
{
    protected $_updates     = [];
    protected $_deletions   = [];

    public function getSubscribedEvents()
    {
        return [
            Events::onFlush,
            Events::postFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em  = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $entities = array_merge(
            $uow->getScheduledEntityInsertions(),
            $uow->getScheduledEntityUpdates()
        );
        foreach ($entities as $entity) {
            if (!$entity instanceof Searchable) {
                continue;
            }
            $changeSet  = $uow->getEntityChangeSet($entity);
            $fields     = $entity->searchFields;
            $changes    = array_intersect($fields, array_keys($changeSet));
            if (!count($changes)) {
                continue;
            }
            $index  = $em->getRepository('Ayre\Core\Index')->id($entity);
            if (!isset($index)) {
                $index               = new Index();
                $index->entity   = \Ayre::entity($entity)->name;
                $index->entityObject = $entity;
            }
            $content = [];
            foreach ($fields as $field) {
                $value = $entity->$field;
                if (is_array($value)) {
                    $temp = (object) ['temp' => []];
                    array_walk_recursive($value, function($value, $name, $temp) {
                        $temp->temp[] = $value;
                    }, $temp);
                    $value = implode(' ', $temp->temp);
                }
                $content[] = $value;
            }
            $index->content = implode(' ', $content);
            $this->_updates[] = $index;
        }

        $entities = array_merge(
            $uow->getScheduledEntityDeletions()
        );
        foreach ($entities as $entity) {
            if (!$entity instanceof Searchable) {
                continue;
            }
            $index  = $em->getRepository('Ayre\Core\Index')->id($entity);
            if (!isset($index)) {
                continue;
            }
            $this->_deletions[] = $index;
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if (!count($this->_updates) && !count($this->_deletions)) {
            return;
        }

        $em = $args->getEntityManager();

        while (count($this->_updates)) {
            $entity = array_shift($this->_updates);
            if (!isset($entity->id)) {
                $entity->entityId = $entity->entityObject->id;
                $em->persist($entity);
            }
        }

        while (count($this->_deletions)) {
            $entity = array_shift($this->_deletions);
            $em->remove($entity);
        }   

        $em->flush();
    }
}