<?php
namespace Ayre\Controller\Structure;

use Ayre,
	Ayre\Entity,
	Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class Node extends Action
{
	public function postDispatch(Request $req, Response $res)
	{
		$req->view->entityType
			= $req->entityType
			= Ayre::type($req->view->entity->getObject());
	}

	public function edit(Request $req, Response $res)
	{
		$node = $this->em('Ayre\Entity\Structure\Node')->fetch($req->node);
		$req->view->node = $node;

		$entity	= $node->content->last;
		if ($entity->status == \Ayre::STATUS_PUBLISHED) {
			$entity = $entity->duplicate();
		}
		$req->view->entity = $wrap = $this->em->wrap($entity);

		if (!$req->isPost()) {
			return;
		}

		$wrap->graphFromArray($req->bodyParams());
		if (!$wrap->isValid()) {
			return;
		}

		if (!$this->em->isPersisted($entity)) {
			$this->em->persist($entity);
		}
		$this->em->flush();

		return $res->redirect($this->url(array(
			'action'	=> 'index',
			'node'		=> null,
		), 'structure'));
	}

}

