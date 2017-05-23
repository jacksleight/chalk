<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk,
	Chalk\Core,
	Chalk\Core\Backend\Controller\Entity,
	Coast\Request,
	Coast\Response;

class Widget extends Entity
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
		$wrap->graphFromArray($req->bodyParams());
      
        if (!$req->post) {
            return;
        }

		if (!$wrap->isValid()) {
			return;
		}

		$req->data->entity = $req->info->name;
		$req->data->params = array_map(function($value) {
			return is_object($value) ? $value->id : $value;
		}, $widget->toArray());
		$req->data->html = $this->view->render('widget/card', [
			'widget' => $widget,
		], 'core')->toString();
	}

	public function delete(Request $req, Response $res)
	{
		$req->data->mode = 'delete';
	}
}