<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Doctrine\ORM;

class EntityManager extends \Coast\Doctrine\ORM\EntityManager
{
    protected $_trackable;

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

    public function trackable(\Ayre\Behaviour\Trackable\Listener $trackable = null)
    {
        if (isset($trackable)) {
            $this->_trackable = $trackable;
            return $this;
        }
        return $this->_trackable;
    }
}