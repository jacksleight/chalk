<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Repository;

use Chalk\Chalk,
    Chalk\Core,
    Chalk\Core\Behaviour\Searchable,
    Chalk\Repository;

class Search extends Repository
{
    public function entities($entities)
    {
        $query = $this->build();
        $wheres = [];
        foreach ($entities as $entity) {
            $wheres[] = "(s.entityType = '" . Chalk::info($entity)->name . "' AND s.entityId = " . $entity->id . ")";
        }
        $query->andWhere(implode(' OR ', $wheres));
        $searches = $query->getQuery()->execute();

        $map = [];
        foreach ($searches as $i => $search) {
            $map["{$search->entityType}_{$search->entityId}"] = $i;
        }
        foreach ($entities as $entity) {
            $key = Chalk::info($entity)->name . '_' . $entity->id;
            if (isset($map[$key])) {
                $search = $searches[$map[$key]];
                $search->entityObject = $entity;
            } else {
                $search = new \Chalk\Core\Search($entity);
                $this->_em->persist($search);
                $searches[] = $search;
            }
        }

        return $searches;
    }

    public function search($query, $classes = array())
    {
        $conn = $this->_em->getConnection();

        $query   = $conn->quote($query);
        $classes = (array) $classes;
        foreach ($classes as $i => $class) {
            $classes[$i] = $conn->quote(Chalk::info($class)->name);
        }

        $where  = count($classes)
            ? "AND s.entityType IN(" . implode(', ', $classes) . ")"
            : null;
        $table = Chalk::info('Chalk\Core\Search')->name;
        return $conn->query("
            SELECT s.entityType, s.entityId,
                MATCH(s.content) AGAINST ({$query}) AS score
            FROM {$table} AS s
            WHERE MATCH(s.content) AGAINST ({$query} IN BOOLEAN MODE)
                {$where}
            ORDER BY score DESC, s.entityId DESC
        ")->fetchAll();
    }
}