<?php
$app->router
	->all('index', "{controller}?/{action}?/{id}?", [
		'controller' => 'index',
		'action'     => 'index',
		'id'    	 => null,
	])
	->all('passwordRequest', "password-request", [
		'controller' => 'auth',
		'action'     => 'password-request',
	])
	->all('passwordReset', "password-reset/{token}", [
		'controller' => 'auth',
		'action'     => 'password-reset',
	])
	->all('login', "login", [
		'controller' => 'auth',
		'action'     => 'login',
	])
	->all('logout', "logout", [
		'controller' => 'auth',
		'action'     => 'logout',
	])
	->all('profile', "profile", [
		'controller' => 'profile',
		'action'     => 'edit',
	])
	->all('prefs', "prefs", [
		'controller' => 'index',
		'action'     => 'prefs',
	])
	->all('content', "content/{entity}?/{action}?/{content}?", [
		'controller' => 'content',
		'action'     => 'index',
		'entity'     => null,
		'content'    => null,
	])
	->all('setting', "setting/{controller}?/{action}?/{id}?", [
		'controller' => 'setting',
		'action'     => 'index',
		'id'  	  	 => null,
	])
	->all('widget', "widget/{action}/{entity}", [
		'controller' => 'widget',
	])
	->all('structure', "structure/{action}?/{structure}?", [
		'controller' => 'structure',
		'action'     => 'index',
		'structure'	 => null,
	])
	->all('structure_node', "structure/node/{structure}/{action}?/{node}?", [
		'controller' => 'structure_node',
		'action'     => 'index',
		'node'    	 => null,
	]);