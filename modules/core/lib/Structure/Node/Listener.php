<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Structure\Node;

use Chalk\Core\Structure\Node, 
    Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Event\OnFlushEventArgs,
    Doctrine\ORM\Event\PostFlushEventArgs,
    Doctrine\ORM\Events,
    Doctrine\ORM\UnitOfWork;

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

        $structures = [];
        $entities = array_merge(
            $uow->getScheduledEntityInsertions(),
            $uow->getScheduledEntityUpdates(),
            $uow->getScheduledEntityDeletions()
        );
        foreach ($entities as $entity) {
            if (!$entity instanceof Node) {
                continue;
            }
            if (!in_array($entity->structure, $this->_structures, true)) {
                $this->_structures[] = $entity->structure;
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
                $nodes = $stack;
                array_shift($nodes);
                $node->path = implode('/', array_map(function($node) {
                    return isset($node->slug) ? $node->slug : $node->content->slug;
                }, $nodes));
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
