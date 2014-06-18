<?php
namespace Ayre\Core\Controller;

use Ayre,
	Ayre\Core,
	Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class Widget extends Ayre\Controller\Basic
{
	public function preDispatch(Request $req, Response $res)
	{
		$req->view->entity
			= $req->entity
			= Ayre::entity($req->entity);
	}

	public function edit(Request $req, Response $res)
	{
		if (!$this->view->has($req->entity->local->path, $req->entity->module->name)) {
			return $res->json(['widget' => $widget]);
		}

		$class = $req->entity->class;
		$widget = new $class();
		$req->view->widget = $wrap = $this->em->wrap($widget);
		$wrap->graphFromArray($req->queryParams());

		if (!$req->isPost()) {
			return;
		}

		$wrap->graphFromArray($req->bodyParams());
		if (!$wrap->isValid()) {
			return;
		}

		return $res->json([
			'entity'	=> $req->entity->name,
			'params'	=> array_map(function($value) {
				return is_object($value) ? (string) $value : $value;
			}, $widget->toArray()),
		]);
	}
}