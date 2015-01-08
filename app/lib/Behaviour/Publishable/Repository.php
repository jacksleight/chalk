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
    public function queryModifier(QueryBuilder $query, array $criteria = array())
    {
        $criteria = $criteria + [
            'isPublished'   => Chalk::isFrontend(),
            'isPublishable' => false,
        ];

        if ($criteria['isPublished']) {
            $query->andWhere("c.status IN (:statuses) AND UTC_TIMESTAMP() >= c.publishDate");
            $query->setParameter('statuses', [Chalk::STATUS_PUBLISHED]);
        }
        if ($criteria['isPublishable']) {
            $query->andWhere("c.status IN (:statuses)");
            $query->setParameter('statuses', [Chalk::STATUS_DRAFT, Chalk::STATUS_PENDING]);
        }
    }
}