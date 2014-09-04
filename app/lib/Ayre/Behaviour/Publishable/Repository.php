<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Behaviour\Publishable;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder,
    Doctrine\ORM\Query;

trait Repository
{
    public function query(QueryBuilder $query, array $criteria = array())
    {
        $criteria = $criteria + [
            'isPublished'   => false,
        ];
        
        if ($criteria['isPublished']) {
            $query->andWhere("c.status IN ('published') AND UTC_TIMESTAMP() >= c.publishDate");
        }
    }
}