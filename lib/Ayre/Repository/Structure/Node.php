<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Repository\Structure;

use Ayre\Entity;

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

	public function fetchAll(Entity\Structure\Node $node = null, $include = false, $depth = null)
	{
		$params = [
			'root'		=> $node->root_id,
			'left'		=> $node->left,
			'right'		=> $node->right,
			'minLevel'	=> $node->level,
		];
		$qb = $this->createQueryBuilder('n')
			->addSelect('c', 'cv')
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
		$qb = $this->createQueryBuilder('n')
			->addSelect('c', 'cv')
			->innerJoin('n.content', 'c')
			->innerJoin('c.versions', 'cv')
			->andWhere('cv.next IS NULL')
			->from($this->_entityName, 'nc')
			->andWhere('nc.left > n.left AND nc.left < n.right')
			->andWhere('n = :node')
			->orderBy('nc.left');
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

	public function fetchByPath(Entity\Structure $struct, $path, $published = false)
	{
		$params = [
			'root'	=> $struct->root->id,
			'path'	=> $path,
		];
		$qb = $this->createQueryBuilder("n")
			->addSelect('c', 'cv')
			->innerJoin("n.content", "c")
			->innerJoin("c.versions", "cv")
			->andWhere("n.root_id = :root")
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