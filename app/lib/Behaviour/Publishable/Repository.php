<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Behaviour\Publishable;

use Chalk\Chalk,
    Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder,
    Doctrine\ORM\Query;

trait Repository
{
    public function publishableQueryModifier(QueryBuilder $query, array $criteria = array(), $alias = null)
    {
        $alias = isset($alias)
            ? $alias
            : $this->_alias;

        $criteria = $criteria + [
            'isPublished'   => Chalk::isFrontend(),
            'isPublishable' => false,
        ];

        if ($criteria['isPublished']) {
            $query->andWhere("{$alias}.status IN (:statuses) AND UTC_TIMESTAMP() >= {$alias}.publishDate");
            $query->setParameter('statuses', [Chalk::STATUS_PUBLISHED]);
        }
        if ($criteria['isPublishable']) {
            $query->andWhere("{$alias}.status IN (:statuses)");
            $query->setParameter('statuses', [Chalk::STATUS_DRAFT, Chalk::STATUS_PENDING]);
        }
    }
}