<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Repository;

use Ayre,
    Ayre\Entity,
    Ayre\Behaviour\Searchable,
    Ayre\Repository;

class Index extends Repository
{
    public function fetch(Searchable $entity)
    {
        $index = $this->_em->createQueryBuilder()
            ->select("i")
            ->from("\Ayre\Entity\Index", "i")
            ->andWhere("i.entity_type = :entity_type")
            ->andWhere("i.entity_id = :entity_id")
            ->getQuery()
            ->setParameters([
                'entity_type' => Ayre::type($entity)->type,
                'entity_id'   => $entity->id,
            ])          
            ->getOneOrNullResult();
        return $index;
    }

    public function query($query, $classes = array())
    {
        $conn = $this->_em->getConnection();

        $query   = $conn->quote($query);
        $classes = (array) $classes;
        foreach ($classes as $i => $class) {
            $classes[$i] = $conn->quote($class);
        }

        $where  = count($classes)
            ? "AND i.entity_type IN(" . implode(', ', $classes) . ")"
            : null;
        return $conn->query("
            SELECT i.entity_type, i.entity_id,
                MATCH(i.content) AGAINST ({$query} IN BOOLEAN MODE) AS score
            FROM core_index AS s
            WHERE MATCH(i.content) AGAINST ({$query} IN BOOLEAN MODE)
                {$where}
            ORDER BY score DESC
        ")->fetchAll();
    }
}