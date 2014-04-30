<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Doctrine\ORM;

class EntityManager extends \Coast\Doctrine\ORM\EntityManager
{
    protected $_blameable;
    protected $_uploadable;

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

    public function blameable(\Gedmo\Blameable\BlameableListener $blameable = null)
    {
        if (isset($blameable)) {
            $this->_blameable = $blameable;
            return $this;
        }
        return $this->_blameable;
    }

    public function uploadable(\Gedmo\Uploadable\UploadableListener $uploadable = null)
    {
        if (isset($uploadable)) {
            $this->_uploadable = $uploadable;
            \Ayre\Entity\File::uploadable($this->_uploadable);
            return $this;
        }
        return $this->_uploadable;
    }
}