<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Behaviour\Searchable;

use Chalk\Chalk,
    Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder,
    Doctrine\ORM\Query;

trait Repository
{
    protected function _searchable_modify(QueryBuilder $query, $params = array(), $extra = false, $alias = null, $class = null)
    {
        $alias = isset($alias)
            ? $alias
            : $this->alias();
        $class = isset($class)
            ? $class
            : $this->_class->name;

        $params = $params + [
            'search' => null,
        ];

        if (isset($params['search'])) {
            $classes = array_merge(
                [$class],
                $this->_em->getClassMetadata($class)->subClasses
            );
            $results = $this->_em->getRepository('Chalk\Core\Search')
                ->search($params['search'], $classes);
            $ids = \Coast\array_column($results, 'entityId');
            $query
                ->andWhere("{$alias}.id IN (:ids)")
                ->orderBy("FIELD({$alias}.id, :ids)")
                ->setParameter('ids', $ids);
        }
    }
}