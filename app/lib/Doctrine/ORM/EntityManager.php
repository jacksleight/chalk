<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
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
}