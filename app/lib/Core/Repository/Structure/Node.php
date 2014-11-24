<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Repository\Structure;

use Chalk\Core\Structure,
    Chalk\Repository,
    Chalk\Behaviour\Publishable,
    Chalk\Core\Structure\Node as StructureNode;

class Node extends Repository
{
    use Publishable\Repository {
        Publishable\Repository::queryModifier as publishableQueryModifier;
    }

    protected $_alias = 'n';

    public function query(array $criteria = array(), $sort = null, $limit = null, $offset = null)
    {
        $query = parent::query($criteria, $sort, $limit, $offset);

        $criteria = $criteria + [
            'structure'  => null,
            'children'   => null,
            'parents'    => null,
            'siblings'   => null,
            'depth'      => null,
            'isIncluded' => false,
            'isVisible'  => false,
        ];

        $query
            ->addSelect("c", "cv")
            ->leftJoin("n.content", "c")
            ->leftJoin("c.versions", "cv")
            ->andWhere("cv.next IS NULL");

        $depth = null;
        if (isset($criteria['structure'])) {
            $query
                ->andWhere('n.structure = :structure')
                ->setParameter('structure', $criteria['structure']);
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
        if ($criteria['isVisible']) {
            $query
                ->andWhere("n.isHidden = false");
        }

        if (!isset($sort)) {
            $query->orderBy("n.left");
        }

        $this->publishableQueryModifier($query, $criteria);

        return $query;
    }

    public function children(StructureNode $node, $isIncluded = false, $depth = null, array $criteria = array())
    {
        return $this->query(array_merge($criteria, [
                'children'   => $node,
                'isIncluded' => $isIncluded,
                'depth'      => $depth,
            ]))
            ->getQuery()
            ->getResult('coast_array');
    }

    public function parents(StructureNode $node, $isIncluded = false, $depth = null, $isReversed = false, array $criteria = array())
    {
        return $this->query(array_merge($criteria, [
                'parents'    => $node,
                'isIncluded' => $isIncluded,
                'depth'      => $depth,
            ]), ['left', $isReversed ? 'DESC' : 'ASC'])
            ->getQuery()
            ->getResult('coast_array');
    }

    public function siblings(StructureNode $node, $isIncluded = false, array $criteria = array())
    {
        return $this->query(array_merge($criteria, [
                'siblings'   => $node,
                'isIncluded' => $isIncluded,
            ]))
            ->getQuery()
            ->getResult('coast_array');
    }

    public function tree(StructureNode $node, $isIncluded = false, $isMerged = false, $depth = null, array $criteria = array())
    {
        $nodes = $this->query(array_merge($criteria, [
                'children'   => $node,
                'isIncluded' => $isIncluded,
                'depth'      => $depth,
            ]))
            ->getQuery()
            ->getResult('coast_array');
        $map = [];
        foreach ($nodes as $mapped) {
            $map[$mapped->id] = $mapped;
        }
 
        $isFetched = isset($map[$node->id]);
        if (!$isFetched) {
            $root = (object) $node->toArray();
            array_unshift($nodes, $root);
            $map[$root->id] = $root;
        } else {
            $root = $nodes[0];
        }

        foreach ($nodes as $node) {
            $node->children = [];
        }
        foreach ($nodes as $node) {
            if (isset($node->parentId)) {
                $map[$node->parentId]->children[] = $node;
            }
        }

        if ($isIncluded) {
            if ($isMerged) {
                $array = $root->children;
                if ($isFetched) {
                    $root->children = [];
                    array_unshift($array, $root);
                }
                return $array;
            } else if ($isFetched) {
                return [$root];
            } else {
                return [];
            }
        } else {
            return $root->children;
        }
    }

    public function treeIterator(StructureNode $node, $isIncluded = false, $isMerged = false, $depth = null, array $criteria = array())
    {
        $nodes = $this->tree($node, $isIncluded, $isMerged, $depth, $criteria);
        return new \RecursiveIteratorIterator(
            new \Chalk\Core\Structure\Node\Iterator($nodes),
            \RecursiveIteratorIterator::SELF_FIRST);
    }
}