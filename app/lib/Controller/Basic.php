<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Controller;

use Chalk\App as Chalk,
	Coast\Controller\Action,
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
		$name = $req->info->local->name;
		$entity = isset($req->id)
			? $this->em($req->info)->id($req->id)
			: $this->em($req->info)->create();
		$req->view->$name = $wrap = $this->em->wrap($entity);

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