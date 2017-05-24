<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Behaviour\Searchable;

use Chalk\Chalk,
    Chalk\Core\Search,
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
            $searches = $em->getRepository('Chalk\Core\Search')
                ->entities($this->_deletions);
            foreach ($searches as $search) {
                $em->remove($search);
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

        $searches = $em->getRepository('Chalk\Core\Search')
            ->entities($this->_updates);
        foreach ($searches as $search) {
            $search->reindex();
        }
        $this->_updates = [];
    
        $em->flush();
    }
}