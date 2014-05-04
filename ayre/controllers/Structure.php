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

		$data	= json_decode($req->data);
		$repo	= $this->em('Ayre\Entity\Structure');
		$struct	= $repo->fetch($req->id);
		$nodes	= $repo->fetchNodes($struct);

		$map	= [];
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
				: $struct->root;
			$node = $map[$value->id];
			$node->sort = $i;
			$node->parent->children->removeElement($node);
			$node->parent = $map[$parent->id];
		}

		$this->em->flush();
		$this->em('Ayre\Entity\Structure\Node')->reorder($struct->root, 'sort');

		return $res->redirect($this->url(array(
			'action' => 'index',
		)));
	}

	public function add(Request $req, Response $res)
	{
		$req->view->entityType = Ayre::type(isset($req->entityType) ? $req->entityType : 'Ayre\Entity\Content');

		$wrap = $this->em->wrap($index = new \Ayre\Index());
		$wrap->graphFromArray($req->queryParams());
		$req->view->index = $wrap;

		if (!$req->isPost()) {
			return;
		}

		$wrap->graphFromArray($req->bodyParams());
		$struct = $this->em('Ayre\Entity\Structure')->fetch($req->id);

		foreach ($index->contents as $content) {
			$node = new \Ayre\Entity\Structure\Node();
			$node->parent = $struct->root;
			$node->content = $content->master;
			$this->em->persist($node);
			$this->em->flush();
		}

		return $res->redirect($this->url(array(
			'action' => 'index',
		)));
	}
}

