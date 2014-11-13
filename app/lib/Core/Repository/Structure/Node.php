<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Repository\Structure;

use Chalk\Core\Structure,
    Chalk\Repository,
    Chalk\Core\Structure\Node as StructureNode;

class Node extends Repository
{
    protected $_alias = 'n';

    public function query(array $criteria = array(), $sort = null, $limit = null, $offset = null)
    {
        $query = parent::query($criteria, $sort, $limit, $offset);

        $criteria = $criteria + [
            'structure'  => null,
            'children'   => null,
            'parents'    => null,
            'depth'      => null,
            'isIncluded' => false,
        ];

        $query
            ->addSelect('c')
            ->innerJoin('n.content', 'c')
            ->andWhere('c.next IS NULL');

        $depth = null;
        if (isset($criteria['structure'])) {
            $query
                ->andWhere('n.structure = :structure')
                ->setParameter('structure', $criteria['structure']);
            if (isset($criteria['depth'])) {
                $query
                    ->andWhere('n.depth <= :depth')
                    ->setParameter('depth', $criteria['depth']);
            }
        } else if (isset($criteria['children'])) {
            $query
                ->andWhere('n.structure = :structure AND n.left >= :left AND n.right <= :right')
                ->setParameter('structure', $criteria['children']->structure)
                ->setParameter('left', $criteria['children']->left)
                ->setParameter('right', $criteria['children']->right);
            if (isset($criteria['depth'])) {
                $query
                    ->andWhere('n.depth <= :depth')
                    ->setParameter('depth', $criteria['children']->depth + $criteria['depth']);
            }
            if (!$criteria['isIncluded']) {
                $query
                    ->andWhere('n != :exclude')
                    ->setParameter('exclude', $criteria['children']);
            }
        } else if (isset($criteria['parents'])) {
            $query
                ->from($this->_entityName, 'nt')
                ->andWhere('n.structure = :structure AND nt.left >= n.left AND nt.left <= n.right')
                ->andWhere('nt = :node')
                ->setParameter('structure', $criteria['parents']->structure)
                ->setParameter('node', $criteria['parents']);            
            if (isset($criteria['depth'])) {
                $query
                    ->andWhere('n.depth >= :depth')
                    ->setParameter('depth', $criteria['parents']->depth - $criteria['depth']);
            }
            if (!$criteria['isIncluded']) {
                $query
                    ->andWhere('n != :exclude')
                    ->setParameter('exclude', $criteria['parents']);
            }
        } else if (isset($criteria['siblings'])) {
            $query
                ->andWhere('n.parent = :parent')
                ->setParameter('parent', $criteria['siblings']->parent);
            if (!$criteria['isIncluded']) {
                $query
                    ->andWhere('n != :exclude')
                    ->setParameter('exclude', $criteria['siblings']);
            }
        }

        if (!isset($sort)) {
            $query->orderBy("n.left");
        }

        return $query;
    }

    public function children(StructureNode $node, array $criteria = array())
    {
        return $this->all(array_merge($criteria, [
            'children' => $node,
        ]));
    }

    public function parents(StructureNode $node, array $criteria = array(), $reverse = false)
    {
        return $this->all(array_merge($criteria, [
            'parents' => $node,
        ]), ['left', $reverse ? 'DESC' : 'ASC']);
    }

    public function siblings(StructureNode $node, array $criteria = array())
    {
        return $this->all(array_merge($criteria, [
            'siblings' => $node,
        ]));
    }

    public function tree(StructureNode $node, array $criteria = array())
    {
        return $this->initTree($this->all(array_merge($criteria, [
            'children'   => $node,
            'isIncluded' => true,
        ])))[0];
    }

    public function initTree($nodes)
    {
        foreach ($nodes as $node) {
            $node->children->setInitialized(true);
            $node->children->clear();
        }
        foreach ($nodes as $node) {
            if (isset($node->parent)) {
                $node->parent->children->add($node);
            }
        }
        return $nodes;
    }
}