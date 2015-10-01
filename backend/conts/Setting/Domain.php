<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller\Setting;

use Chalk\Chalk,
	Chalk\Controller\Basic,
	Coast\Request,
	Coast\Response;

class Domain extends Basic
{
	protected $_entityClass = 'Chalk\Core\Domain';

	public function preDispatch(Request $req, Response $res)
	{
		parent::preDispatch($req, $res);
		if (!in_array($req->user->role, ['administrator', 'developer'])) {
			return $this->forward('forbidden', 'index');
		}
	}

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
		return $res->redirect($this->url([]));
	}
}