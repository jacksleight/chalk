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

class Search extends Repository
{
    public function fetch(Searchable $entity)
    {
        $search = $this->_em->createQueryBuilder()
            ->select("s")
            ->from("\Ayre\Entity\Search", "s")
            ->andWhere("s.class = :class")
            ->andWhere("s.class_id = :class_id")
            ->getQuery()
            ->setParameters([
                'class'     => Ayre::resolve($entity)->short,
                'class_id'  => $entity->id,
            ])          
            ->getOneOrNullResult();
        return $search;
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
            ? "AND s.class IN(" . implode(', ', $classes) . ")"
            : null;
        return $conn->query("
            SELECT s.class, s.class_id,
                MATCH(s.content) AGAINST ({$query} IN BOOLEAN MODE) AS score
            FROM search AS s
            WHERE MATCH(s.content) AGAINST ({$query} IN BOOLEAN MODE)
                {$where}
            ORDER BY score DESC
        ")->fetchAll();
    }
}