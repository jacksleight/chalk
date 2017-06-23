<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Behaviour\Trackable;

use Chalk\Chalk;
use Chalk\Core\Content;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use DateTime;

trait Repository
{
    protected function _trackable_modify(QueryBuilder $query, array $params = array(), $extra = false, $alias = null)
    {
        $alias = isset($alias)
            ? $alias
            : $this->alias();

        $params = $params + [
            'createDateMin' => null,
            'createDateMax' => null,
            'updateDateMin' => null,
            'updateDateMax' => null,
            'createUsers'   => null,
            'updateUsers'   => null,
        ];

        if (isset($params['createDateMin'])) {
            $createDateMin = $params['createDateMin'] instanceof DateTime
                ? $params['createDateMin']
                : new DateTime($params['createDateMin']);
            $query
                ->andWhere("{$alias}.createDate >= :createDateMin")
                ->setParameter('createDateMin', $createDateMin);
        }
        if (isset($params['createDateMax'])) {
            $createDateMax = $params['createDateMax'] instanceof DateTime
                ? $params['createDateMax']
                : new DateTime($params['createDateMax']);
            $query
                ->andWhere("{$alias}.createDate <= :createDateMax")
                ->setParameter('createDateMax', $createDateMax);
        }

        if (isset($params['updateDateMin'])) {
            $updateDateMin = $params['updateDateMin'] instanceof DateTime
                ? $params['updateDateMin']
                : new DateTime($params['updateDateMin']);
            $query
                ->andWhere("{$alias}.updateDate >= :updateDateMin")
                ->setParameter('updateDateMin', $updateDateMin);
        }
        if (isset($params['updateDateMax'])) {
            $updateDateMax = $params['updateDateMax'] instanceof DateTime
                ? $params['updateDateMax']
                : new DateTime($params['updateDateMax']);
            $query
                ->andWhere("{$alias}.updateDate <= :updateDateMax")
                ->setParameter('updateDateMax', $updateDateMax);
        }

        if (isset($params['createUsers'])) {
            $query
                ->andWhere("{$alias}.createUser IN (:createUsers)")
                ->setParameter('createUsers', $params['createUsers']);
        }

        if (isset($params['updateUsers'])) {
            $query
                ->andWhere("{$alias}.createUser IN (:updateUsers)")
                ->setParameter('updateUsers', $params['updateUsers']);
        }
    }
}