<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Repository;

use Chalk\Chalk,
    Chalk\Core,
    Chalk\Behaviour\Searchable,
    Chalk\Repository;

class Index extends Repository
{
    public function fetch($id)
    {
        $index = $this->_em->createQueryBuilder()
            ->select("i")
            ->from("\Chalk\Core\Index", "i")
            ->andWhere("i.entity = :entity")
            ->andWhere("i.entityId = :entityId")
            ->getQuery()
            ->setParameters([
                'entity'   => \Chalk\Chalk::info($id)->name,
                'entityId' => $id->id,
            ])          
            ->getOneOrNullResult();
        return $index;
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
            ? "AND i.entity IN(" . implode(', ', $classes) . ")"
            : null;
        $table = \Chalk\Chalk::info('Chalk\Core\Index')->name;
        return $conn->query("
            SELECT i.entity, i.entityId,
                MATCH(i.content) AGAINST ({$query} IN BOOLEAN MODE) AS score
            FROM {$table} AS i
            WHERE MATCH(i.content) AGAINST ({$query} IN BOOLEAN MODE)
                {$where}
            ORDER BY score DESC
        ")->fetchAll();
    }
}