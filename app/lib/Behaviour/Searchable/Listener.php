<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Behaviour\Searchable;

use Chalk\Chalk,
    Chalk\Core\Index,
    Chalk\Behaviour\Searchable,
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
            $index  = $em->getRepository('Chalk\Core\Index')->one(['entity' => $entity]);
            if (!isset($index)) {
                $index               = new Index();
                $index->entityObject = $entity;
            }
            $content = implode(' ', $entity->searchContent);
            $content = strip_tags($content);
            $content = html_entity_decode($content, ENT_COMPAT | ENT_HTML5, 'utf-8');
            $content = mb_strtolower($content, 'utf-8');
            $content = preg_replace("/['’]/u", '', $content);
            $content = preg_replace("/[^[:alnum:]]+/u", ' ', $content);
            $content = trim($content);
            $index->content = $content;
            $this->_updates[] = $index;
        }

        $entities = array_merge(
            $uow->getScheduledEntityDeletions()
        );
        foreach ($entities as $entity) {
            if (!$entity instanceof Searchable) {
                continue;
            }
            $index  = $em->getRepository('Chalk\Core\Index')->one(['entity' => $entity]);
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
            $index = array_shift($this->_updates);
            if (!isset($index->id)) {
                $index->entityType = \Chalk\Chalk::info($index->entityObject)->name;
                $index->entityId   = $index->entityObject->id;
                $em->persist($index);
            }
        }

        while (count($this->_deletions)) {
            $index = array_shift($this->_deletions);
            $em->remove($index);
        }   

        $em->flush();
    }
}