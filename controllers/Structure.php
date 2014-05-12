<?php
namespace Ayre\Controller;

use Ayre,
	Ayre\Entity,
	Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class Structure extends Action
{
	public function index(Request $req, Response $res)
	{}

	public function reorder(Request $req, Response $res)
	{
		if (!$req->isPost()) {
			throw new \Ayre\Exception("Reorder action only accepts POST requests");
		}
		if (!$req->data) {
			return $res->redirect($this->url(array(
				'action' => 'index',
			)));
		}

		$data		= json_decode($req->data);
		$repo		= $this->em('Ayre\Entity\Structure');
		$structure	= $repo->fetch($req->structure);
		$nodes		= $repo->fetchNodes($structure);

		$map = [];
		foreach ($nodes as $node) {
			$map[$node->id] = $node;
		}		

		$it = new \RecursiveIteratorIterator(
			new Entity\Structure\Iterator($data),
			\RecursiveIteratorIterator::SELF_FIRST);
		$stack = [];
		foreach ($it as $i => $value) {
			array_splice($stack, $it->getDepth(), count($stack), array($value));
			$depth  = $it->getDepth();
			$parent = $depth > 0
				? $stack[$depth - 1]
				: $structure->root;
			$node = $map[$value->id];
			$node->parent->children->removeElement($node);
			$node->parent = $map[$parent->id];
			$node->sort	= $i;
		}
		$this->em->flush();

		return $res->redirect($this->url(array(
			'action' => 'index',
		)));
	}
}

