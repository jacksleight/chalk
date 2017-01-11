<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Doctrine\ORM;

use Chalk\App as Chalk;
use Doctrine\Common\EventSubscriber;

class EntityManager extends \Coast\Doctrine\ORM\EntityManager
{
    protected $_listeners = [];

    public function __invoke($class)
    {
        return $this->getRepository(Chalk::info($class)->class);
    }

    public function wrap($object, $allowed = null, array $md = null)
    {
        if ($object instanceof \Toast\Entity) {
            return new \Toast\Wrapper\Entity($object, $allowed);
        } elseif ($object instanceof \Doctrine\Common\Collections\Collection) {
            return new \Toast\Wrapper\Collection($object, $allowed, null, null, $md);
        } else {
            throw new \Exception();
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

    public function objects(array $items, $classes = null, $filters = null, array $opts = array())
    {
        if (!isset($classes)) {
            $classes = ['__ROOT__' => null];
        } else if (!is_array($classes)) {
            $classes = ['__ROOT__' => $classes];        
        }

        $map = [];
        foreach ($items as $i => &$item) {
            foreach ($classes as $key => $class) {
                if ($key == '__ROOT__') {
                    $value = &$item;
                } else {
                    $value = &$item[$key];
                }
                if (!isset($value)) {
                    continue;
                }
                if (isset($class)) {
                    $class = $class;
                    $id    = $value;
                } else if (is_array($value)) {
                    $class = $value['__CLASS__'];
                    $id    = $value['id'];
                }
                if (!isset($map[$class][$id])) {
                    $map[$class][$id] = [];
                }
                $map[$class][$id][] = &$value;
            }
        }

        foreach ($map as $class => $ids) {
            $entities = $this->__invoke($class)->all([
                'ids' => array_keys($ids),
            ], $opts);
            foreach ($entities as $entity) {
                foreach ($ids[$entity['id']] as &$ref) {
                    $ref = $entity;
                }
            }
            foreach ($ids as $id => &$refs) {
                foreach ($refs as &$ref) {
                    if (is_object($ref)) {
                        continue;
                    }
                    $ref = null;
                }
            }
        }

        if (!isset($filters)) {
            return $items;
        } else if ($filters === true) {
            $filters = ['__ROOT__'];
        }

        foreach ($items as $i => &$item) {
            foreach ($filters as $key) {
                if ($key == '__ROOT__') {
                    if (!isset($item)) {
                        unset($items[$i]);
                    }
                } else {
                    if (!isset($item[$key])) {
                        unset($items[$i]);
                    }
                }
            }
        }

        return $items;
    }
}