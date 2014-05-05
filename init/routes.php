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
	->all('structure', "structure/{action}?/{structure}?", [
		'controller' => 'structure',
		'action'     => 'index',
		'structure'	 => null,
	])
	->all('structure_node', "structure/{structure}/node/{action}?/{node}?", [
		'controller' => 'structure_node',
		'action'     => 'index',
		'node'    	 => null,
	]);