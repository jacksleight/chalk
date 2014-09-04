<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Core\Repository;

use Ayre,
    Ayre\Core,
    Ayre\Behaviour\Loggable,
    Ayre\Repository;

class Log extends Repository
{
    public function fetchAll(array $criteria = array())
    {
        $params = [];
        $qb = $this->_em->createQueryBuilder()
            ->select("l")
            ->from("\Ayre\Core\Log", "l");
        if (isset($critera['entity'])) {
            $qb ->andWhere("l.entity = :entity")
                ->andWhere("l.entityId = :entityId");
            $params['entity'] = \Ayre::entity($entity)->name;
            $params['entityId']   = $entity->id;
        }
        return $qb
            ->getQuery()
            ->setParameters($params)        
            ->getResult();
    }
}