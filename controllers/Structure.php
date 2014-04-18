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

	public function node(Request $req, Response $res)
	{
		$tree	= $this->entity('Ayre\Entity\Tree')->fetch($req->id);
		$nodes	= $this->entity('Ayre\Entity\Tree\Node')->getChildren($tree->root, null, null, null, true);
		$parent = $nodes[array_rand($nodes)];

		$node = new \Ayre\Entity\Tree\Node();
		$node->parent = $parent;
		$this->entity->persist($node);
		$this->entity->flush();

		return $res->redirect($this->url(array(
			'action' => 'index',
		)));
	}
}