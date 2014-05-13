<?php
namespace Ayre\Controller\Structure;

use Ayre,
	Ayre\Entity,
	Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class Node extends Action
{
	public function add(Request $req, Response $res)
	{
		$req->view->entityType = Ayre::type('Ayre\Entity\Content');

		$wrap = $this->em->wrap($index = new \Ayre\Index());
		$wrap->graphFromArray($req->queryParams());
		$req->view->index = $wrap;

		if (!$req->isPost()) {
			return;
		}

		$wrap->graphFromArray($req->bodyParams());
		if (isset($req->node)) {
			$parent = $this->em('Ayre\Entity\Structure\Node')->fetch($req->node);
		} else {
			$parent = $this->em('Ayre\Entity\Structure')->fetch($req->structure)->root;
		}

		foreach ($index->contents as $content) {
			$node = new \Ayre\Entity\Structure\Node();
			$node->parent  = $parent;
			$node->contentMaster = $content->master;
			$this->em->persist($node);
			$this->em->flush();
		}

		if (isset($req->node)) {
			return $res->redirect($this->url(array(
				'action' => 'edit',
			)));
		} else {
			return $res->redirect($this->url(array(
				'action' => 'index',
				'node'	 => null,
			), 'structure'));
		}
	}

	public function edit(Request $req, Response $res)
	{
		$node = $this->em('Ayre\Entity\Structure\Node')->fetch($req->node);
		$req->view->node = $wrap = $this->em->wrap($node);

		if (!$req->isPost()) {
			return;
		}

		$wrap->graphFromArray($req->bodyParams());
		if (!$wrap->isValid()) {
			return;
		}

		if (!$this->em->isPersisted($node)) {
			$this->em->persist($node);
		}
		$this->em->flush();

		return $res->redirect($this->url(array()));
	}

	public function delete(Request $req, Response $res)
	{
		$node = $this->em('Ayre\Entity\Structure\Node')->fetch($req->node);

		$parent = $node->parent;
		$parent->id;
		foreach ($node->children as $child) {
			$node->children->removeElement($child);
			$child->parent	= $parent;
			$child->sort	= \Ayre\Entity\Structure\Node::SORT_MAX;
		}
		$this->em->remove($node);
		$this->em->flush();

		return $res->redirect($this->url(array(
			'action'	=> 'index',
			'node'		=> null,
		), 'structure'));
	}
}