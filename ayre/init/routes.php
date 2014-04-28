<?php
$app->router
	->all('index', "{$app->base}/{controller}?/{action}?/{id}?", [
		'controller' => 'index',
		'action'     => 'index',
		'id'    	 => null,
	])
	->all('content', "{$app->base}/content/{entityType}?/{action}?/{id}?", [
		'controller' => 'content',
		'action'     => 'index',
		'id'    	 => null,
	])
	->all('structure', "{$app->base}/structure/{action}?/{id}?", [
		'controller' => 'structure',
		'action'     => 'index',
		'id'    	 => null,
	]);