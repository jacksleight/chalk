<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\App;

use Ayre,
	Doctrine\ORM\EntityManager;

class Entity implements \Coast\App\Access
{
    use \Coast\App\Access\Implementation;

    protected $_em;

    public function __construct(EntityManager $em)
    {
    	$this->em($em);
    }

    public function em(EntityManager $em = null)
    {
    	if (isset($em)) {
    		$this->_em = $em;
    		return $this;
    	}
    	return $this->_em;
    }

    public function call($class)
    {
    	return $this->_em->getRepository($class);
    }

    public function __call($name, array $args)
    {
        return call_user_func_array(array($this->_em, $name), $args);
    }

    public function wrap($object, $allowed = null, array $md = null)
    {
        if ($object instanceof \Ayre\Entity) {
            return new \Ayre\Wrapper\Entity($object, $allowed);
        } elseif ($object instanceof \Doctrine\Common\Collections\Collection) {
            return new \Ayre\Wrapper\Collection($object, $allowed, null, null, $md);
        } else {
            throw new \Exception();
        }
    }
    
    public function isPersisted(Ayre\Entity $entity)
    {
        $uow = $this->_em->getUnitOfWork();
        return $uow->getEntityState($entity) == \Doctrine\ORM\UnitOfWork::STATE_MANAGED;
    }

    public function changes(Ayre\Entity $entity)
    {
        $uow = $this->_em->getUnitOfWork();
        $uow->computeChangeSets();
        return $uow->getEntityChangeSet($entity);
    }
}