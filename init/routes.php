<?php
$app->router
	->all('index', "{controller}?/{action}?/{id}?", [
		'controller' => 'index',
		'action'     => 'index',
		'id'    	 => null,
	])
	->all('login', "login", [
		'controller' => 'auth',
		'action'     => 'login',
	])
	->all('logout', "logout", [
		'controller' => 'auth',
		'action'     => 'logout',
	])
	->all('prefs', "prefs", [
		'controller' => 'index',
		'action'     => 'prefs',
	])
	->all('content', "content/{action}?/{entity}?/{content}?", [
		'controller' => 'content',
		'action'     => 'redirect',
		'entity' => null,
		'content'    => null,
	])
	->all('widget', "widget/{action}/{entity}", [
		'controller' => 'widget',
	])
	->all('structure', "structure/{action}?/{structure}?", [
		'controller' => 'structure',
		'action'     => 'redirect',
		'structure'	 => null,
	])
	->all('structure_node', "structure/node/{structure}/{action}?/{node}?", [
		'controller' => 'structure_node',
		'action'     => 'index',
		'node'    	 => null,
	]);