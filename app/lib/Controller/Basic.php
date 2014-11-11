<?php
namespace Chalk\Controller;

use Chalk\Chalk,
	Coast\App\Controller\Action,
	Coast\Request,
	Coast\Response;

class Basic extends Action
{
	protected $_entityClass;

	public function preDispatch(Request $req, Response $res)
	{
		$req->view->info
			= $req->info
			= Chalk::info($this->_entityClass);
	}

	public function index(Request $req, Response $res)
	{}

	public function edit(Request $req, Response $res)
	{
		$var = $req->info->local->var;
		$entity = isset($req->id)
			? $this->em($req->info)->id($req->id)
			: $this->em($req->info)->create();
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

		$this->notify("{$req->info->singular} <strong>{$entity->name}</strong> was saved successfully", 'positive');
		return $res->redirect($this->url(array(
			'action'	=> null,
			'id'		=> null,
		)));
	}

	public function delete(Request $req, Response $res)
	{
		$entity = $this->em($req->info)->find($req->id);

		$this->em->remove($entity);
		$this->em->flush();

		$this->notify("{$req->info->singular} <strong>{$entity->name}</strong> was deleted successfully", 'positive');
		return $res->redirect($this->url(array(
			'action'	=> null,
			'id'		=> null,
		)));
	}
}