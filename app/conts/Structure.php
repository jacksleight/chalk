<?php
namespace Chalk\Core\Controller;

use Chalk\Chalk,
	Chalk\Core\Structure\Iterator,
	Coast\App\Controller\Action,
	Coast\Request,
	Coast\Response;

class Structure extends Action
{
	public function index(Request $req, Response $res)
	{
		if (!$req->structure) {
			$domain = $this->em('Chalk\Core\Domain')->fetchFirst();
			return $res->redirect($this->url([
				'action'	=> 'index',
				'structure'	=> $domain->structure->id,
			]));
		}
	}

	public function reorder(Request $req, Response $res)
	{
		if (!$req->isPost()) {
			throw new \Chalk\Exception("Reorder action only accepts POST requests");
		}
		if (!$req->data) {
			return $res->redirect($this->url(array(
				'action' => 'index',
			)));
		}

		$data		= json_decode($req->data);
		$repo		= $this->em('Chalk\Core\Structure');
		$structure	= $repo->id($req->structure);
		$nodes		= $repo->fetchNodes($structure);

		$map = [];
		foreach ($nodes as $node) {
			$map[$node->id] = $node;
		}		

		$it = new \RecursiveIteratorIterator(
			new Iterator($data),
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

		$this->notify("Content was moved successfully", 'positive');
		return $res->redirect($this->url(array(
			'action' => 'index',
		)));
	}
}

