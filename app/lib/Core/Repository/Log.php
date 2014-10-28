<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Repository;

use Chalk\Chalk,
    Chalk\Core,
    Chalk\Behaviour\Loggable,
    Chalk\Repository;

class Log extends Repository
{
    public function fetchAll(array $criteria = array())
    {
        $params = [];
        $qb = $this->_em->createQueryBuilder()
            ->select("l")
            ->from("\Chalk\Core\Log", "l");
        if (isset($critera['entity'])) {
            $qb ->andWhere("l.entity = :entity")
                ->andWhere("l.entityId = :entityId");
            $params['entity']   = \Chalk\Chalk::info($entity)->name;
            $params['entityId'] = $entity->id;
        }
        return $qb
            ->getQuery()
            ->setParameters($params)        
            ->getResult();
    }
}