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
    protected $_alias = 'l';

    public function query(array $criteria = array(), $sort = null, $limit = null, $offset = null)
    {
        $query = parent::query($criteria, $sort, $limit, $offset);

        $criteria = $criteria + [
            'entity' => null,
        ];
        
        if (isset($criteria['entity'])) {
            $query
                ->andWhere("l.entity = :entity AND l.entityId = :entityId")
                ->setParameter('entity', Chalk::info($entity)->name)
                ->setParameter('entityId', $entity->id);
        }

        return $query;
    }
}