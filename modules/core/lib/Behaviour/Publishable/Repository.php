<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Behaviour\Publishable;

use Chalk\App as Chalk;
use Chalk\Core\Content;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use DateTime;

trait Repository
{
    public function publishableQueryModifier(QueryBuilder $query, array $criteria = array(), $alias = null)
    {
        $alias = isset($alias)
            ? $alias
            : $this->alias();

        $criteria = $criteria + [
            'isPublished'    => Chalk::isFrontend(),
            'isPublishable'  => false,
            'publishDateMin' => null,
            'publishDateMax' => null,
        ];

        if ($criteria['isPublished']) {
            $query->andWhere("{$alias}.status IN (:statuses) AND UTC_TIMESTAMP() >= {$alias}.publishDate");
            $query->setParameter('statuses', [Chalk::STATUS_PUBLISHED]);
        }
        if ($criteria['isPublishable']) {
            $query->andWhere("{$alias}.status IN (:statuses)");
            $query->setParameter('statuses', [Chalk::STATUS_DRAFT, Chalk::STATUS_PENDING]);
        }

        if (isset($criteria['publishDateMin'])) {
            $publishDateMin = $criteria['publishDateMin'] instanceof DateTime
                ? $criteria['publishDateMin']
                : new DateTime($criteria['publishDateMin']);
            $query
                ->andWhere("{$alias}.publishDate >= :publishDateMin")
                ->setParameter('publishDateMin', $publishDateMin);
        }
        if (isset($criteria['publishDateMax'])) {
            $publishDateMax = $criteria['publishDateMax'] instanceof DateTime
                ? $criteria['publishDateMax']
                : new DateTime($criteria['publishDateMax']);
            $query
                ->andWhere("{$alias}.publishDate <= :publishDateMax")
                ->setParameter('publishDateMax', $publishDateMax);
        }
    }

    public function publishMonths(array $params = array(), array $opts = array())
    {
        $query = $this->build($params + [
            'sort' => ['publishDate', 'DESC'],
        ]);

        $query
            ->select("
                DATE_FORMAT({$this->alias()}.publishDate, '%Y') AS year,
                DATE_FORMAT({$this->alias()}.publishDate, '%m') AS month,
                DATE_FORMAT({$this->alias()}.publishDate, '%Y-%m') AS yearMonth,
                {$this->alias()}.publishDate AS date,
                COUNT({$this->alias()}) AS contentCount
            ")
            ->andWhere("{$this->alias()}.publishDate IS NOT NULL")
            ->groupBy("yearMonth");

        $query = $this->prepare($query, [
            'hydrate' => \Chalk\Repository::HYDRATE_ARRAY,
        ] + $opts);
        return $this->fetch($query);
    }

    public function publishYears(array $params = array(), array $opts = array())
    {
        $years = [];

        foreach ($this->publishMonths() as $month) {
            if (!isset($years[$month['year']])) {
                $years[$month['year']] = [
                    'year'         => $month['year'],
                    'date'         => $month['date'],
                    'months'       => [],
                    'contentCount' => 0,
                ];
            }
            $years[$month['year']]['months'][] = $month;
            $years[$month['year']]['contentCount'] += $month['contentCount'];
        }
        $years = array_values($years);

        return $years;
    }  
}