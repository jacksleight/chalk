<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Repository;

use Chalk\Chalk;
use Chalk\Core;
use Chalk\Core\Behaviour\Logable;
use Chalk\Repository;

class Log extends Repository
{
    public function build(array $params = array(), $extra = false)
    {
        $query = parent::build($params, $extra);

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