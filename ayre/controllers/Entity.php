<?php
namespace Ayre\Controller;

use Ayre,
	Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class Entity extends Action
{
	protected $_entityClass;

	public function preDispatch(Request $req, Response $res)
	{
		$req->view->entityType
			= $req->entityType
			= Ayre::type($this->_entityClass);
	}

	public function postDispatch(Request $req, Response $res)
	{
		$path = "{$req->entityType->local->path}/{$req->action}";

		var_dump($path);

		if (!$this->view->has($path)) {
			$req->view->path = "entity/{$req->action}";
		}
	}

	public function index(Request $req, Response $res)
	{}

	public function edit(Request $req, Response $res)
	{
		$req->view->entity = $wrap = $this->em->wrap(
			$entity = $this->em($req->entityType->class)->fetchOrCreate($req->id)
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
		$entity = $this->em($req->entityType->class)->find($req->id);

		$this->em->remove($entity);
		$this->em->flush();

		return $res->redirect($this->url(array(
			'action'	=> null,
			'id'		=> null,
		)));
	}
}