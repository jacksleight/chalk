<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk,
	Chalk\Core\Backend\Controller\Entity,
	Coast\Request,
	Coast\Response;

class Structure extends Entity
{
	protected $_entityClass = 'Chalk\Core\Structure';

	public function preDispatch(Request $req, Response $res)
	{
		parent::preDispatch($req, $res);
		if (!in_array($req->user->role, ['developer'])) {
			return $this->forward('forbidden', 'index');
		}
	}

	// public function index(Request $req, Response $res)
	// {
	// 	if (!$req->structure) {
	// 		$domain = $this->em('Chalk\Core\Domain')->one();
	// 		return $res->redirect($this->url([
	// 			'structure'	=> $domain->structures->first()->id,
	// 			'action'	=> 'edit',
	// 			'node'		=> $domain->structures->first()->root->id,
	// 		], 'core_structure_node', true));
	// 	} if (!$req->node) {
	// 		$structure = $this->em('Chalk\Core\Structure')->id($req->structure);
	// 		return $res->redirect($this->url([
	// 			'structure'	=> $structure->id,
	// 			'action'	=> 'edit',
	// 			'node'		=> $structure->root->id,
	// 		], 'core_structure_node', true));
	// 	}
	// }

	// public function reorder(Request $req, Response $res)
	// {
	// 	if (!$req->isPost()) {
	// 		throw new \Chalk\Exception("Reorder action only accepts POST requests");
	// 	}
	// 	if (!$req->nodeData) {
	// 		return $res->redirect($this->url(array(
	// 			'action' => 'index',
	// 		)));
	// 	}

	// 	$data = json_decode($req->nodeData);
	// 	$structure = $this
	// 		->em('Chalk\Core\Structure')
	// 		->id($req->structure);
	// 	$nodes = $this
	// 		->em('Chalk\Core\Structure\Node')
	// 		->all(['structure' => $structure]);

	// 	$map = [];
	// 	foreach ($nodes as $node) {
	// 		$map[$node->id] = $node;
	// 	}		

	// 	$it = new \RecursiveIteratorIterator(
	// 		new Iterator($data),
	// 		\RecursiveIteratorIterator::SELF_FIRST);
	// 	$stack = [];
	// 	foreach ($it as $i => $value) {
	// 		array_splice($stack, $it->getDepth(), count($stack), array($value));
	// 		$depth  = $it->getDepth();
	// 		$parent = $depth > 0
	// 			? $stack[$depth - 1]
	// 			: $structure->root;
	// 		$node = $map[$value->id];
	// 		$node->parent->children->removeElement($node);
	// 		$node->parent = $map[$parent->id];
	// 		$node->sort	= $i;
	// 	}
	// 	$this->em->flush();

	// 	$this->notify("Content was moved successfully", 'positive');
	// 	if (isset($req->redirect)) {
	// 		return $res->redirect($req->redirect);
	// 	} else {
	// 		return $res->redirect($this->url(array(
	// 			'action' => 'index',
	// 		)));
	// 	}
	// }
}