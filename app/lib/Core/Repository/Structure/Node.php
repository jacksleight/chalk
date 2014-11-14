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
        $nodes = $this->all(array_merge($criteria, [
            'children'   => $node,
            'isIncluded' => $isIncluded,
            'depth'      => $depth,
        ]));

        foreach ($nodes as $node) {
            $node->children->setInitialized(true);
            $node->children->clear();
        }

        return $nodes;
    }

    public function parents(StructureNode $node, $isIncluded = false, $depth = null, $isReversed = false, array $criteria = array())
    {
        $nodes = $this->all(array_merge($criteria, [
            'parents'    => $node,
            'isIncluded' => $isIncluded,
            'depth'      => $depth,
        ]), ['left', $isReversed ? 'DESC' : 'ASC']);

        foreach ($nodes as $node) {
            $node->children->setInitialized(true);
            $node->children->clear();
        }

        return $nodes;
    }

    public function siblings(StructureNode $node, $isIncluded = false, array $criteria = array())
    {
        $nodes = $this->all(array_merge($criteria, [
            'siblings'   => $node,
            'isIncluded' => $isIncluded,
        ]));

        foreach ($nodes as $node) {
            $node->children->setInitialized(true);
            $node->children->clear();
        }

        return $nodes;
    }

    public function tree(StructureNode $node, $isIncluded = false, $isMerged = false, $depth = null, array $criteria = array())
    {
        $root = $node;

        $nodes = $this->all(array_merge($criteria, [
            'children'   => $root,
            'isIncluded' => $isIncluded,
            'depth'      => $depth,
        ]));

        $isFetched = in_array($root, $nodes, true);
        if (!$isFetched) {
            array_unshift($nodes, $root);
        }
        foreach ($nodes as $node) {
            $node->children->setInitialized(true);
            $node->children->clear();
        }
        foreach ($nodes as $node) {
            if (isset($node->parent)) {
                $node->parent->children->add($node);
            }
        }

        if ($isIncluded) {
            if ($isMerged) {
                $array = $root->children->toArray();
                if ($isFetched) {
                    $root->children->clear();
                    array_unshift($array, $root);
                }
                return $array;
            } else if ($isFetched) {
                return [$root];
            } else {
                return [];
            }
        } else {
            return $root->children->toArray();
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