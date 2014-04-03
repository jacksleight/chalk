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

    public function call($short)
    {
    	return $this->_em->getRepository(Ayre::resolveShort($short)->class);
    }

    public function __call($name, array $args)
    {
        return call_user_func_array(array($this->_em, $name), $args);
    }
}