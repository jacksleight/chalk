<?php
namespace Ayre\Controller;

use Ayre,
	Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class Basic extends Action
{
	protected $_entityClass;

	public function preDispatch(Request $req, Response $res)
	{
		$req->view->entity
			= $req->entity
			= Ayre::entity($this->_entityClass);
	}

	public function index(Request $req, Response $res)
	{}

	public function edit(Request $req, Response $res)
	{
		$var = $req->entity->local->var;
		$req->view->$var = $wrap = $this->em->wrap(
			$entity = $this->em($req->entity->class)->fetchOrCreate($req->id)
		);

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
			'action'	=> null,
			'id'		=> null,
		)));
	}

	public function delete(Request $req, Response $res)
	{
		$entity = $this->em($req->entity->class)->find($req->id);

		$this->em->remove($entity);
		$this->em->flush();

		return $res->redirect($this->url(array(
			'action'	=> null,
			'id'		=> null,
		)));
	}
}