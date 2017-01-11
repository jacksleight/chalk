<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Repository;

use Chalk\App as Chalk,
    Chalk\Core,
    Chalk\Core\Behaviour\Loggable,
    Chalk\Repository;

class Log extends Repository
{
    public function build(array $params = array())
    {
        $query = parent::build($params);

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