<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Behaviour\Publishable;

use Chalk\Chalk;
use Chalk\Core\Content;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use DateTime;

trait Repository
{
    protected function _publishable_modify(QueryBuilder $query, array $params = array(), $extra = false, $alias = null)
    {
        $alias = isset($alias)
            ? $alias
            : $this->alias();

        $params = $params + [
            'isPublished'    => Chalk::isFrontend(),
            'isPublishable'  => false,
            'publishDateMin' => null,
            'publishDateMax' => null,
        ];

        if ($params['isPublished']) {
            $query->andWhere("{$alias}.status IN (:statuses) AND UTC_TIMESTAMP() >= {$alias}.publishDate");
            $query->setParameter('statuses', [Chalk::STATUS_PUBLISHED]);
        }
        if ($params['isPublishable']) {
            $query->andWhere("{$alias}.status IN (:statuses)");
            $query->setParameter('statuses', [Chalk::STATUS_DRAFT, Chalk::STATUS_PENDING]);
        }

        if (isset($params['publishDateMin'])) {
            $publishDateMin = $params['publishDateMin'] instanceof DateTime
                ? $params['publishDateMin']
                : new DateTime($params['publishDateMin']);
            $query
                ->andWhere("{$alias}.publishDate >= :publishDateMin")
                ->setParameter('publishDateMin', $publishDateMin);
        }
        if (isset($params['publishDateMax'])) {
            $publishDateMax = $params['publishDateMax'] instanceof DateTime
                ? $params['publishDateMax']
                : new DateTime($params['publishDateMax']);
            $query
                ->andWhere("{$alias}.publishDate <= :publishDateMax")
                ->setParameter('publishDateMax', $publishDateMax);
        }
    }

    public function publishMonths(array $params = array(), array $opts = array())
    {
        $query = $this->build($params + [
                'sort' => ["{$this->alias()}.publishDate", "DESC"],
            ])
            ->resetDQLParts(['select', 'from', 'join'])
            ->select("
                DATE_FORMAT({$this->alias()}.publishDate, '%Y') AS year,
                DATE_FORMAT({$this->alias()}.publishDate, '%m') AS month,
                DATE_FORMAT({$this->alias()}.publishDate, '%Y-%m') AS yearMonth,
                {$this->alias()}.publishDate AS date,
                COUNT({$this->alias()}) AS contentCount
            ")
            ->from("{$this->_entityName}", "{$this->alias()}")
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