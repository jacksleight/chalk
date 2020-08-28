<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Chalk\Core;
use Chalk\Core\Backend\Controller\Entity;
use Coast\Request;
use Coast\Response;
use Coast\Controller\Action;

class Widget extends Action
{
	public function preDispatch(Request $req, Response $res)
	{
        $this->info = Chalk::info($this->model->type);

        $req->view->info = $this->info;
	}

	public function update(Request $req, Response $res)
	{
		$module = $this->app->chalk->module($this->info->module->name);
		$widget = $module->widgetObject($this->info, $this->model->state);
		$widget = $this->em->wrap($widget);

		$req->view->widget = $widget;
      
        if ($this->model->method == 'delete') {
            $req->data->method = 'delete';
            return;
        } else if ($this->model->method != 'post') {
        	$widget->graphFromArray($req->bodyParams());
            return;
        }

        $widget->graphFromArray($req->bodyParams());
		if (!$widget->isValid()) {
			return;
		}

		$req->data->widget = [
			'name'   => $this->info->name,
			'params' => array_map(function($value) {
				return is_object($value) ? $value->id : $value;
			}, $widget->toArray()),
			'html' => $this->view->render('element/card-widget', [
				'widget' => $widget,
			], 'core')->toString(),
		];
	}
}