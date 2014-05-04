<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Repository\Structure;

use Ayre\Entity,
	Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class Node extends NestedTreeRepository
{
	public function fetchAll(Entity\Structure\Node $node, $include = false, $depth = null)
	{
		$params = [
			'root'		=> $node->root_id,
			'left'		=> $node->left,
			'right'		=> $node->right,
			'minLevel'	=> $node->level,
		];
		$qb = $this->createQueryBuilder('n')
			->innerJoin('n.content', 'c')
			->innerJoin('c.versions', 'cv')
			->andWhere('cv.next IS NULL')
			->andWhere('n.root_id = :root')
			->andWhere('n.left > :left AND n.right < :right')
			->andWhere('n.level > :minLevel')
			->orderBy('n.left');
		if (isset($depth)) {
			$qb->andWhere('n.level <= :maxLevel');
			$params['maxLevel'] = $node->level + $depth;
		}
		$nodes = $qb
			->getQuery()
			->setParameters($params)
			->getResult();
		if ($include) {
			array_unshift($nodes, $node);
		}
		return $nodes;
	}

	public function fetchDescendants(Entity\Structure\Node $node, $include = false, $depth = null)
	{
		return $this->fetchAll($node, $include, $depth);
	}

	public function fetchChildren(Entity\Structure\Node $node, $include = false)
	{
		return $this->fetchAll($node, $include, 1);
	}

	public function fetchParents(Entity\Structure\Node $node, $include = false, $reverse = false)
	{
		$params = [
			'node' => $node,
		];
		$qb = $this->createQueryBuilder('np')
			->innerJoin('np.content', 'c')
			->innerJoin('c.versions', 'cv')
			->andWhere('cv.next IS NULL')
			->from($this->_entityName, 'n')
			->andWhere('n.left > np.left AND n.left < np.right')
			->andWhere('n = :node')
			->orderBy('n.left');
		$nodes = $qb
			->getQuery()
			->setParameters($params)
			->getResult();
		if ($include) {
			array_push($nodes, $node);
		}
		if ($reverse) {
			$nodes = array_reverse($nodes);
		}
		return $nodes;
	}

	public function fetchSiblings(Entity\Structure\Node $node, $include = false)
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

	public function fetchTree(Entity\Structure\Node $node, $include = false, $depth = null)
	{
		$this->fetchAll($node, false, $depth);
		return $include
			? [$node]
			: $node->children;
	}
}