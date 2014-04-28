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
		$tree	= $this->em('Ayre\Entity\Structure')->fetch($req->id);
		$nodes	= $this->em('Ayre\Entity\Structure\Node')->getChildren($tree->root, null, null, null, true);
		$parent = $nodes[array_rand($nodes)];

		$node = new \Ayre\Entity\Structure\Node();
		$node->parent = $parent;
		$this->em->persist($node);
		$this->em->flush();

		return $res->redirect($this->url(array(
			'action' => 'index',
		)));
	}
}