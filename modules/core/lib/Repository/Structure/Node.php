<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Repository\Structure;

use Chalk\App as Chalk,
    Chalk\Core\Structure,
    Chalk\Repository,
    Chalk\Core\Behaviour\Publishable,
    Chalk\Core\Structure\Node as StructureNode;

class Node extends Repository
{
    use Publishable\Repository;

    protected $_sort = 'left';

    public function build(array $params = array())
    {
        $query = parent::build($params);

        $params = $params + [
            'structure'  => null,
            'children'   => null,
            'parents'    => null,
            'siblings'   => null,
            'depth'      => null,
            'isIncluded' => false,
            'isVisible'  => Chalk::isFrontend(),
        ];

        $query
            ->addSelect("c")
            ->leftJoin("n.content", "c");

        $depth = null;
        if (isset($params['structure'])) {
            $query
                ->andWhere('n.structure = :structure')
                ->setParameter('structure', $params['structure']);
        } else if (isset($params['children'])) {
            $query
                ->andWhere('n.structure = :structure AND n.left >= :left AND n.right <= :right')
                ->setParameter('structure', $params['children']['structureId'])
                ->setParameter('left', $params['children']['left'])
                ->setParameter('right', $params['children']['right']);
            if (isset($params['depth'])) {
                $query
                    ->andWhere('n.depth <= :depth')
                    ->setParameter('depth', $params['children']['depth'] + $params['depth']);
            }
            if (!$params['isIncluded']) {
                $query
                    ->andWhere('n != :exclude')
                    ->setParameter('exclude', $params['children']['id']);
            }
        } else if (isset($params['parents'])) {
            $query
                ->from($this->_entityName, 'nt')
                ->andWhere('n.structure = :structure AND nt.left >= n.left AND nt.left <= n.right')
                ->andWhere('nt = :node')
                ->setParameter('structure', $params['parents']['structureId'])
                ->setParameter('node', $params['parents']['id']);            
            if (isset($params['depth'])) {
                $query
                    ->andWhere('n.depth >= :depth')
                    ->setParameter('depth', $params['parents']['depth'] - $params['depth']);
            }
            if (!$params['isIncluded']) {
                $query
                    ->andWhere('n != :exclude')
                    ->setParameter('exclude', $params['parents']['id']);
            }
        } else if (isset($params['siblings'])) {
            $query
                ->andWhere('n.parent = :parent')
                ->setParameter('parent', $params['siblings']['parentId']);
            if (!$params['isIncluded']) {
                $query
                    ->andWhere('n != :exclude')
                    ->setParameter('exclude', $params['siblings']['id']);
            }
        }
        if ($params['isVisible']) {
            $query
                ->andWhere("n.isHidden = false");
        }

        $this->publishable_modify($query, $params, 'c');

        return $query;
    }

    public function children($node, $isIncluded = false, $depth = null, array $params = array())
    {
        $query = $this->build(array_merge($params, [
            'children'   => $node,
            'isIncluded' => $isIncluded,
            'depth'      => $depth,
        ]));
        $query = $this->prepare($query, [
            'hydrate'    => Repository::HYDRATE_ARRAY,
        ]);    
        return $this->fetch($query);
    }

    public function parents($node, $isIncluded = false, $depth = null, $isReversed = false, array $params = array())
    {
        $query = $this->build(array_merge($params, [
            'parents'    => $node,
            'isIncluded' => $isIncluded,
            'depth'      => $depth,
            'sort'       => ['left', $isReversed ? 'DESC' : 'ASC'],
        ]));
        $query = $this->prepare($query, [
            'hydrate'    => Repository::HYDRATE_ARRAY,
        ]);    
        return $this->fetch($query);
    }

    public function siblings($node, $isIncluded = false, array $params = array())
    {
        $query = $this->build(array_merge($params, [
            'siblings'   => $node,
            'isIncluded' => $isIncluded,
        ]));
        $query = $this->prepare($query, [
            'hydrate'    => Repository::HYDRATE_ARRAY,
        ]);    
        return $this->fetch($query);
    }

    public function tree($node, $isIncluded = false, $isMerged = false, $depth = null, array $params = array())
    {
        $query = $this->build(array_merge($params, [
            'children'   => $node,
            'isIncluded' => $isIncluded,
            'depth'      => $depth,
        ]));
        $query = $this->prepare($query, [
            'hydrate'    => Repository::HYDRATE_ARRAY,
        ]);
        $nodes = $this->fetch($query);

        $map = [];
        foreach ($nodes as $i => $mapped) {
            $map[$mapped['id']] = &$nodes[$i];
        }

        $isFetched = isset($map[$node['id']]);
        if (!$isFetched) {
            $root = is_object($node)
                ? $node->toArray()
                : $node;
            $nodes[]          = &$root;
            $map[$root['id']] = &$root;
        } else {
            $root = &$nodes[0];
        }

        foreach ($nodes as $i => $node) {
            $nodes[$i]['children'] = [];
        }
        foreach ($nodes as $i => $node) {
            if (isset($nodes[$i]['parentId'])) {
                $map[$nodes[$i]['parentId']]['children'][] = &$nodes[$i];
            }
        }

        if ($isIncluded) {
            if ($isMerged) {
                $array = $root['children'];
                if ($isFetched) {
                    $root['children'] = [];
                    array_unshift($array, $root);
                }
                return $array;
            } else if ($isFetched) {
                return [$root];
            } else {
                return [];
            }
        } else {
            return $root['children'];
        }
    }

    public function treeIterator(StructureNode $node, $isIncluded = false, $isMerged = false, $depth = null, array $params = array())
    {
        $nodes = $this->tree($node, $isIncluded, $isMerged, $depth, $params);
        return new \RecursiveIteratorIterator(
            new \Chalk\Core\Structure\Node\Iterator($nodes),
            \RecursiveIteratorIterator::SELF_FIRST);
    }
}