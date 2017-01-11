<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Structure\Node;

use Chalk\Core\Content;
use Chalk\Core\Structure;
use Chalk\Core\Structure\Node;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;

class Listener implements EventSubscriber
{
    protected $_structures = [];

    protected $_isFlushing = false;

    public function getSubscribedEvents()
    {
        return [
            Events::onFlush,
            Events::postFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        if ($this->_isFlushing) {
            return;
        }

        $em  = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $entities = array_merge(
            $uow->getScheduledEntityInsertions(),
            $uow->getScheduledEntityUpdates(),
            $uow->getScheduledEntityDeletions()
        );
        foreach ($entities as $entity) {
            $structures = [];
            if ($entity instanceof Structure) {
                $structures[] = $entity;
            } else if ($entity instanceof Node) {
                $structures[] = $entity->structure;
            } else if ($entity instanceof Content) {
                foreach ($entity->nodes as $node) {
                    $structures[] = $node->structure;
                }
            } else {
                continue;
            }
            foreach ($structures as $structure) {
                if (!in_array($structure, $this->_structures, true)) {
                    $this->_structures[] = $structure;
                }
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if ($this->_isFlushing || !count($this->_structures)) {
            return;
        }

        $em  = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($this->_structures as $structure) {
            $nodes = $em
                ->getRepository('Chalk\Core\Structure\Node')
                ->all([
                    'structure' => $structure,
                    'sort'      => 'sort',
                ]);
            $root = null;
            foreach ($nodes as $node) {
                $node->children->setInitialized(true);
                $node->children->clear();
                if (!isset($node->parent)) {
                    $root = $node;
                }
            }
            foreach ($nodes as $node) {
                if (isset($node->parent)) {
                    $node->parent->children->add($node);
                }
            }
            $tree = new \RecursiveIteratorIterator(
                new \Chalk\Core\Structure\Node\Iterator([$root]),
                \RecursiveIteratorIterator::SELF_FIRST);
            $j     = 0;
            $stack = [];
            foreach ($tree as $i => $node) {
                $slice = array_splice($stack, $tree->getDepth(), count($stack), [$node]);
                foreach (array_reverse($slice) as $reverse) {
                    $reverse->right = $j++;
                }
                $node->left  = $j++;
                $node->sort  = $i;
                $node->depth = $tree->getDepth();
                $nodes       = $stack;
                array_shift($nodes);               
                $parts = array_map(function($node) {
                    return isset($node->slug) ? $node->slug : $node->content->slug;
                }, $nodes);
                if (isset($structure->path)) {
                    array_unshift($parts, $structure->path);
                }
                $node->path = implode('/', $parts);
            }
            foreach (array_reverse($stack) as $reverse) {
                $reverse->right = $j++;
            }
        }
        $this->_structures = [];

        $this->_isFlushing = true;
        $em->flush();
        $this->_isFlushing = false;
    }
}
