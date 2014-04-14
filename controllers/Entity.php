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
		if (!$this->view->has($path)) {
			$req->view->path = "entity/{$req->action}";
		}
	}

	public function index(Request $req, Response $res)
	{}

	public function edit(Request $req, Response $res)
	{
		$req->view->entity = $wrap = $this->entity->wrap(
			$entity = $this->entity($req->entityType->class)->findOrCreate($req->id)
		);

		if (!$req->isPost()) {
			return;
		}

		$wrap->graphFromArray($req->bodyParams());
		if (!$wrap->graphIsValid()) {
			return;
		}

		if (!$this->entity->isPersisted($entity)) {
			$this->entity->persist($entity);
		}
		$this->entity->flush();

		return $res->redirect($this->url(array(
			'action'	=> null,
			'id'		=> null,
		)));
	}

	public function delete(Request $req, Response $res)
	{
		$entity = $this->entity($req->entityType->class)->find($req->id);

		$this->entity->remove($entity);
		$this->entity->flush();

		return $res->redirect($this->url(array(
			'action'	=> null,
			'id'		=> null,
		)));
	}
}