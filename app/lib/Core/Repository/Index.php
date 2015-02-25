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
    protected $_alias = 'i';

    public function query(array $criteria = array(), $sort = null, $limit = null, $offset = null)
    {
        $query = parent::query($criteria, $sort, $limit, $offset);

        $criteria = $criteria + [
            'entity' => null,
        ];
        
        if (isset($criteria['entity'])) {
            $query
                ->andWhere("i.entityType = :entityType AND i.entityId = :entityId")
                ->setParameter('entityType', Chalk::info($criteria['entity'])->name)
                ->setParameter('entityId', $criteria['entity']->id);
        }

        return $query;
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