<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Repository\Structure;

use Ayre\Entity\Structure\Node as EntityNode;

class Node extends \Ayre\Repository
{
    public function fetch($id)
    {
        if (!isset($id)) {
            return;
        }
        return $this->createQueryBuilder('n')
            ->addSelect('c', 'cv')
            ->innerJoin('n.content', 'c')
            ->innerJoin('c.versions', 'cv')
            ->andWhere('cv.next IS NULL')
            ->andWhere('n.id = :id')
            ->getQuery()
            ->setParameters([
                'id' => $id,
            ])
            ->getOneOrNullResult();
    }

    public function fetchAll(array $criteria = array())
    {
        $params = [];
        $qb = $this->createQueryBuilder('n')
            ->addSelect('c', 'cv')
            ->innerJoin('n.content', 'c')
            ->innerJoin('c.versions', 'cv')
            ->andWhere('cv.next IS NULL');
        
        if (isset($criteria['node'])) {
            $qb->andWhere('n.structure = :structure
                AND n.left >= :left
                AND n.right <= :right');
            $params['structure'] = $criteria['node']->structure;
            $params['left']      = $criteria['node']->left;
            $params['right']     = $criteria['node']->right;
            if (!isset($criteria['include']) || !$criteria['include']) {
                $qb->andWhere('n != :node');
                $params['node'] = $criteria['node'];
            }
        } else if (isset($criteria['structure'])) {
            $qb->andWhere('n.structure = :structure');
            $params['structure'] = $criteria['structure'];
        }
        
        if (isset($criteria['depth'])) {
            $depth = isset($criteria['node'])
                ? $criteria['node']->depth + $criteria['depth']
                : $criteria['depth'];
            $qb->andWhere('n.depth <= :depth');
            $params['depth'] = $depth;
        }

        return $qb
            ->getQuery()
            ->setParameters($params)
            ->getResult();
    }

    public function fetchTree(EntityNode $node, $include = false, $depth = null)
    {
        $this->fetchAll([
            'node'    => $node,
            'include' => false,
            'depth'   => $depth,
        ]);
        return $include
            ? [$node]
            : $node->children;
    }

    public function fetchDescendants(EntityNode $node, $include = false, $depth = null)
    {
        return $this->fetchAll([
            'node'    => $node,
            'include' => $include,
            'depth'   => $depth,
        ]);
    }

    public function fetchChildren(EntityNode $node, $include = false)
    {
        return $this->fetchDescendants($node, $include, 1);
    }

    public function fetchParents(EntityNode $node, $include = false, $reverse = false)
    {
        $params = [
            'structure' => $node->structure,
            'node'      => $node,
        ];
        $qb = $this->createQueryBuilder('n')
            ->addSelect('c', 'cv')
            ->innerJoin('n.content', 'c')
            ->innerJoin('c.versions', 'cv')
            ->andWhere('cv.next IS NULL')
            ->from($this->_entityName, 'nc')
            ->andWhere('n.structure = :structure
                AND nc.left >= n.left
                AND nc.left <= n.right')
            ->andWhere('nc = :node')
            ->orderBy('n.left', $reverse ? 'DESC' : 'ASC');
        if (!$include) {
            $qb->andWhere('n != :node');
            $params['node'] = $node;
        }
        $nodes = $qb
            ->getQuery()
            ->setParameters($params)
            ->getResult();
        return $nodes;
    }

    public function fetchSiblings(EntityNode $node, $include = false)
    {
        $nodes = $node->isRoot()
            ? [$node]
            : $this->fetchChildren($node->parent);
        if (!$include) {
            $i = array_search($node, $nodes, true);
            unset($nodes[$i]);
            $nodes = array_values($nodes);
        }
        return $nodes;
    }

    public function fetchByPath(Entity\Structure $struct, $path, $published = false)
    {
        $params = [
            'structure' => $structure,
            'path'      => $path,
        ];
        $qb = $this->createQueryBuilder("n")
            ->addSelect('c', 'cv')
            ->innerJoin("n.content", "c")
            ->innerJoin("c.versions", "cv")
            ->andWhere("n.structure = :structure")
            ->andWhere("n.path = :path");
        if ($published) {
            $qb->andWhere("cv.status = :status");
            $params['status'] = \Ayre::STATUS_PUBLISHED;
        } else {
            $qb->andWhere("cv.next IS NULL");
        }
        return $qb
            ->getQuery()
            ->setParameters($params)
            ->getOneOrNullResult();
    }
}