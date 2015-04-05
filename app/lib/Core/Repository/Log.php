<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
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

    public function query(array $params = array())
    {
        $query = parent::query($params);

        $params = $params + [
            'entity' => null,
        ];
        
        if (isset($params['entity'])) {
            $query
                ->andWhere("l.entity = :entity AND l.entityId = :entityId")
                ->setParameter('entity', Chalk::info($entity)->name)
                ->setParameter('entityId', $entity->id);
        }

        return $query;
    }
}