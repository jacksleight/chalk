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
    public function searchableQueryModifier(QueryBuilder $query, array $criteria = array(), $alias = null, $entity = null)
    {
        $alias = isset($alias)
            ? $alias
            : $this->_alias;
        $entity = isset($entity)
            ? $entity
            : $this->_class->name;

        $criteria = $criteria + [
            'search'         => null,
            'searchEntities' => null,
        ];

        if (isset($criteria['search'])) {
            $entities = isset($criteria['searchEntities'])
                ? isset($criteria['searchEntities'])
                : [$entity];
            $classes = [];
            foreach ($entities as $entity) {
                $info = Chalk::info($entity);
                $classes = array_merge(
                    $classes,
                    [$info->class],
                    $this->_em->getClassMetadata($info->class)->subClasses
                );
            }
            $results = $this->_em->getRepository('Chalk\Core\Index')
                ->search($criteria['search'], $classes);
            $ids = \Coast\array_column($results, 'entityId');
            $query
                ->addSelect("FIELD({$alias}.id, :ids) AS HIDDEN sort")
                ->andWhere("{$alias}.id IN (:ids)")
                ->orderBy("sort")
                ->setParameter('ids', $ids);
        }
    }
}