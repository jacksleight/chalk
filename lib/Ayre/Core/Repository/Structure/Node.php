<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Core\Repository\Structure;

use Ayre\Core\Structure,
    Ayre\Repository,
    Ayre\Core\Structure\Node as StructureNode;

class Node extends Repository
{
    public function fetch($id)
    {
        if (!isset($id)) {
            return;
        }
        return $this->createQueryBuilder('n')
            ->addSelect('c', 'cv')
            ->leftJoin('n.contentMaster', 'c')
            ->leftJoin('c.versions', 'cv')
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
            ->leftJoin('n.contentMaster', 'c')
            ->leftJoin('c.versions', 'cv')
            ->andWhere('cv.next IS NULL')
            ->orderBy('n.sort');
        
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

    public function fetchDescendants(StructureNode $node, $include = false, $depth = null)
    {
        return $this->fetchAll([
            'node'    => $node,
            'include' => $include,
            'depth'   => $depth,
        ]);
    }

    public function fetchChildren(StructureNode $node, $include = false)
    {
        return $this->fetchAll([
            'node'    => $node,
            'include' => $include,
            'depth'   => 1,
        ]);
    }

    public function fetchParents(StructureNode $node, $include = false, $reverse = false)
    {
        $params = [
            'structure' => $node->structure,
            'node'      => $node,
        ];
        $qb = $this->createQueryBuilder('n')
            ->addSelect('c', 'cv')
            ->leftJoin('n.contentMaster', 'c')
            ->leftJoin('c.versions', 'cv')
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

    public function fetchSiblings(StructureNode $node, $include = false)
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

    public function fetchTree(StructureNode $node, $include = false, $depth = null)
    {
        $nodes = $this->fetchAll([
            'node'    => $node,
            'include' => true,
            'depth'   => $depth,
        ]);
        return $include ? [$nodes[0]] : $nodes[0]->children;
    }

    public function fetchByPath(Structure $structure, $path, $published = false)
    {
        $params = [
            'structure' => $structure,
            'path'      => $path,
        ];
        $qb = $this->createQueryBuilder("n")
            ->setMaxResults(1)
            ->addSelect('c', 'cv')
            ->leftJoin("n.contentMaster", "c")
            ->leftJoin("c.versions", "cv")
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