<?php
namespace Chalk\Core\Controller;

use Chalk\Chalk,
	Chalk\Core,
	Chalk\Controller\Basic,
	Coast\Request,
	Coast\Response;

class Widget extends Basic
{
	public function preDispatch(Request $req, Response $res)
	{
		$req->view->info
			= $req->info
			= Chalk::info($req->entity);
	}

	public function edit(Request $req, Response $res)
	{
		$class = $req->info->class;
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
			'entity'	=> $req->info->name,
			'params'	=> array_map(function($value) {
				return is_object($value) ? (string) $value : $value;
			}, $widget->toArray()),
		]);
	}

	public function delete(Request $req, Response $res)
	{
		return $res->json([
			'delete' => 1,
		]);
	}
}