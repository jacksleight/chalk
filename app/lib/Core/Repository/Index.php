<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Repository;

use Chalk\Chalk,
    Chalk\Core,
    Chalk\Behaviour\Searchable,
    Chalk\Repository;

class Index extends Repository
{
    public function entities($entities)
    {
        $query = $this->query();
        $wheres = [];
        foreach ($entities as $entity) {
            $wheres[] = "(i.entityType = '" . Chalk::info($entity)->name . "' AND i.entityId = " . $entity->id . ")";
        }
        $query->andWhere(implode(' OR ', $wheres));
        $indexes = $query->getQuery()->execute();

        $map = [];
        foreach ($indexes as $i => $index) {
            $map["{$index->entityType}_{$index->entityId}"] = $i;
        }
        foreach ($entities as $entity) {
            $key = Chalk::info($entity)->name . '_' . $entity->id;
            if (isset($map[$key])) {
                $index = $indexes[$map[$key]];
                $index->entityObject = $entity;
            } else {
                $index = new \Chalk\Core\Index($entity);
                $this->_em->persist($index);
                $indexes[] = $index;
            }
        }

        return $indexes;
    }

    public function search($query, $classes = array())
    {
        $conn = $this->_em->getConnection();

        $query   = $conn->quote($query);
        $classes = (array) $classes;
        foreach ($classes as $i => $class) {
            $classes[$i] = $conn->quote(\Chalk\Chalk::info($class)->name);
        }

        $where  = count($classes)
            ? "AND i.entityType IN(" . implode(', ', $classes) . ")"
            : null;
        $table = \Chalk\Chalk::info('Chalk\Core\Index')->name;
        return $conn->query("
            SELECT i.entityType, i.entityId,
                MATCH(i.content) AGAINST ({$query} IN BOOLEAN MODE) AS score
            FROM {$table} AS i
            WHERE MATCH(i.content) AGAINST ({$query} IN BOOLEAN MODE)
                {$where}
            ORDER BY score DESC
        ")->fetchAll();
    }
}