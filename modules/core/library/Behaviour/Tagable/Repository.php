<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Behaviour\Tagable;

use Chalk\Chalk;
use Chalk\Core\Content;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use DateTime;

trait Repository
{
    protected function _tagable_modify(QueryBuilder $query, array $params = array(), $extra = false, $alias = null)
    {
        $alias = isset($alias)
            ? $alias
            : $this->alias();

        $params = $params + [
            'tags' => null,
        ];
             
        if (isset($params['tags'])) {
            $tags = (array) $params['tags'];
            if ($tags == ['none']) {
                $query
                    ->andWhere("{$alias}.tags IS EMPTY");
            } else if (count($tags)) {
                $query
                    ->andWhere("{$this->alias()}_tags.id IN(:tags)")
                    ->groupBy("{$this->alias()}.id")
                    ->andHaving("COUNT(DISTINCT {$this->alias()}_tags.id) = :tagsCount")
                    ->setParameter('tags', $tags)
                    ->setParameter('tagsCount', count($tags));
            }
        }

        $query->leftJoin("{$this->alias()}.tags", "{$this->alias()}_tags"); 
        if ($extra) {
            $query->addSelect("{$this->alias()}_tags");
        }

        return $query;
    }
}