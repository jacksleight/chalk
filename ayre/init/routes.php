<?php
$app->router
	->all('index', "{controller}?/{action}?/{id}?", [
		'controller' => 'index',
		'action'     => 'index',
		'id'    	 => null,
	])
	->all('content', "content/{entityType}?/{action}?/{id}?", [
		'controller' => 'content',
		'action'     => 'index',
		'entityType' => 'core-content',
		'id'    	 => null,
	])
	->all('structure', "structure/{action}?/{id}?", [
		'controller' => 'structure',
		'action'     => 'index',
		'id'    	 => null,
	]);