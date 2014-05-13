<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity\Structure\Node;

use Ayre\Entity, 
    Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Event\OnFlushEventArgs,
    Doctrine\ORM\Event\PostFlushEventArgs,
    Doctrine\ORM\Events,
    Doctrine\ORM\UnitOfWork;

class Listener implements EventSubscriber
{
    protected $_structures = [];

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

        $structures = [];
        $entities = array_merge(
            $uow->getScheduledEntityInsertions(),
            $uow->getScheduledEntityUpdates(),
            $uow->getScheduledEntityDeletions()
        );
        foreach ($entities as $entity) {
            if (!$entity instanceof Entity\Structure\Node) {
                continue;
            }
            if (!in_array($entity->structure, $this->_structures, true)) {
                $this->_structures[] = $entity->structure;
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $em  = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        if (!count($this->_structures)) {
            return;
        }

        while (count($this->_structures)) {
            $structure = array_shift($this->_structures);
            $em->getRepository('Ayre\Entity\Structure')->fetchTree($structure);
            $it    = $structure->iterator();
            $j     = 0;
            $stack = [];
            foreach ($it as $i => $node) {
                $slice = array_splice($stack, $it->getDepth(), count($stack), [$node]);
                foreach (array_reverse($slice) as $reverse) {
                    $reverse->right = ++$j;
                }
                $node->left  = ++$j;
                $node->sort  = $i;
                $node->depth = $it->getDepth();
                $nodes = $stack;
                array_shift($nodes);
                $node->path = implode('/', array_map(function($node) {
                    return $node->slugSmart;
                }, $nodes));
            }
            foreach (array_reverse($stack) as $reverse) {
                $reverse->right = ++$j;
            }
        }
        $em->flush();
    }
}
