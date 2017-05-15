<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Behaviour\Searchable;

use Chalk\Chalk,
    Chalk\Core\Index,
    Chalk\Core\Behaviour\Searchable,
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
            $this->_updates[] = $entity;
        }

        $entities = array_merge(
            $uow->getScheduledEntityDeletions()
        );
        foreach ($entities as $entity) {
            if (!$entity instanceof Searchable) {
                continue;
            }
            $this->_deletions[] = $entity;
        }
        if (count($this->_deletions)) {
            $indexes = $em->getRepository('Chalk\Core\Index')
                ->entities($this->_deletions);
            foreach ($indexes as $index) {
                $em->remove($index);
            }
            $this->_deletions = [];
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if (!count($this->_updates)) {
            return;
        }

        $em = $args->getEntityManager();

        $indexes = $em->getRepository('Chalk\Core\Index')
            ->entities($this->_updates);
        foreach ($indexes as $index) {
            $index->reindex();
        }
        $this->_updates = [];
    
        $em->flush();
    }
}