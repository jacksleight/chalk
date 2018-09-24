<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Doctrine\ORM;

use Chalk\Chalk;
use Chalk\Entity;
use Doctrine\Common\EventSubscriber;

class EntityManager extends \Coast\Doctrine\ORM\EntityManager
{
    protected $_listeners = [];

    public function __invoke($class)
    {
        return $this->getRepository(Chalk::info($class)->class);
    }

    public function proxy($class, $id)
    {
        return $this->getReference(Chalk::info($class)->class, $id);
    }

    public function wrap($object, $allowed = null, array $md = null)
    {
        if ($object instanceof \Toast\Entity) {
            return new \Toast\Wrapper\Entity($object, $allowed);
        } elseif ($object instanceof \Doctrine\Common\Collections\Collection) {
            return new \Toast\Wrapper\Collection($object, $allowed, null, null, $md);
        } else {
            throw new \Exception("Invalid toast wrap class '" . get_class($object) . "'");
        }
    }

    public function dir($name, \Coast\Dir $dir)
    {
        $this
            ->getConfiguration()
            ->getMetadataDriverImpl()
            ->addPaths([$name => $dir->name()]);
    }

    public function listener($name, EventSubscriber $listener = null)
    {
        if (func_num_args() > 1) {
            $this->_listeners[$name] = $listener;
            $this
                ->getEventManager()
                ->addEventSubscriber($listener);
            return $this;
        }
        return isset($this->_listeners[$name])
            ? $this->_listeners[$name]
            : null;
    }

    public function refs($items, $keys = null, array $opts = array())
    {
        if (!isset($keys)) {
            $keys = ['__ROOT__'];
        }
        $map = [];
        foreach ($keys as $key) {
            foreach ($items as &$item) {
                if ($key == '__ROOT__') {
                    $ref = &$item;
                } else {
                    $ref = &$item[$key];
                }
                $ref = Chalk::ref($ref);
                $map[$ref['type']][$ref['id']][] = &$ref;
            }
        }
        foreach ($map as $type => $ids) {
            $entities = $this->__invoke($type)->all([
                'ids' => array_keys($ids),
            ], $opts);
            foreach ($entities as $entity) {
                foreach ($ids[$entity['id']] as &$ref) {
                    $ref[0] = $entity;
                }
            }
        }
        return $items;
    }

    public function ref($ref, array $opts = array())
    {
        return $this->refs([$ref], null, $opts)[0];
    }

    public function objects(array $items, $classes = null, $filters = null, array $opts = array())
    {
        echo 'FIX EM->OBJECTS';
        die;
        return $this->refs($items, $classes, $opts);
    }
}