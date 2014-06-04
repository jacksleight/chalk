<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Core\Repository;

use Ayre,
    Ayre\Core,
    Ayre\Behaviour\Searchable,
    Ayre\Repository;

class Index extends Repository
{
    public function fetch($id)
    {
        $index = $this->_em->createQueryBuilder()
            ->select("i")
            ->from("\Ayre\Core\Index", "i")
            ->andWhere("i.entityType = :entityType")
            ->andWhere("i.entityId = :entityId")
            ->getQuery()
            ->setParameters([
                'entityType' => \Ayre::type($id)->name,
                'entityId'   => $id->id,
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
            $classes[$i] = $conn->quote($class);
        }

        $where  = count($classes)
            ? "AND i.entityType IN(" . implode(', ', $classes) . ")"
            : null;
        return $conn->query("
            SELECT i.entityType, i.entityId,
                MATCH(i.content) AGAINST ({$query} IN BOOLEAN MODE) AS score
            FROM core_index AS i
            WHERE MATCH(i.content) AGAINST ({$query} IN BOOLEAN MODE)
                {$where}
            ORDER BY score DESC
        ")->fetchAll();
    }
}