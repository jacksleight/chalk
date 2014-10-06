<?php
namespace Chalk\Controller;

use Chalk,
	Coast\App\Controller\Action,
	Coast\Request,
	Coast\Response;

class Basic extends Action
{
	protected $_entityClass;

	public function preDispatch(Request $req, Response $res)
	{
		$req->view->entity
			= $req->entity
			= Chalk::entity($this->_entityClass);
	}

	public function index(Request $req, Response $res)
	{}

	public function edit(Request $req, Response $res)
	{
		$var = $req->entity->local->var;
		$entity = isset($req->id)
			? $this->em($req->entity)->id($req->id)
			: $this->em($req->entity)->create();
		$req->view->$var = $wrap = $this->em->wrap($entity);

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
		$entity = $this->em($req->entity)->find($req->id);

		$this->em->remove($entity);
		$this->em->flush();

		return $res->redirect($this->url(array(
			'action'	=> null,
			'id'		=> null,
		)));
	}
}