<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Behaviour\Searchable;

use Chalk\Chalk,
    Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder,
    Doctrine\ORM\Query;

trait Repository
{
    public function searchableQueryModifier(QueryBuilder $query, array $params = array())
    {
        $alias = isset($alias)
            ? $alias
            : $this->alias();

        $params = $params + [
            'search' => null,
        ];

        if (isset($params['search'])) {
            $info = Chalk::info($this->_class->name);
            $classes = array_merge(
                [$info->class],
                $this->_em->getClassMetadata($info->class)->subClasses
            );
            $results = $this->_em->getRepository('Chalk\Core\Index')
                ->search($params['search'], $classes);
            $ids = \Coast\array_column($results, 'entityId');
            $query
                ->andWhere("{$alias}.id IN (:ids)")
                ->orderBy("FIELD({$alias}.id, :ids)")
                ->setParameter('ids', $ids);
        }
    }
}