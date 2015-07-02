<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

use Coast\Router;

$routes = [
	'index' => ['all', "{controller}?/{action}?/{id}?", [
		'controller' => 'index',
		'action'     => 'index',
		'id'    	 => null,
	]],
	'about' => ['all', "about", [
		'controller' => 'auth',
		'action'     => 'about',
	]],
	'sandbox' => ['all', "sandbox", [
		'controller' => 'index',
		'action'     => 'sandbox',
	]],
	'passwordRequest' => ['all', "password-request", [
		'controller' => 'auth',
		'action'     => 'password-request',
	]],
	'passwordReset' => ['all', "password-reset/{token}", [
		'controller' => 'auth',
		'action'     => 'password-reset',
	]],
	'login' => ['all', "login", [
		'controller' => 'auth',
		'action'     => 'login',
	]],
	'logout' => ['all', "logout", [
		'controller' => 'auth',
		'action'     => 'logout',
	]],
	'profile' => ['all', "profile", [
		'controller' => 'profile',
		'action'     => 'edit',
	]],
	'prefs' => ['all', "prefs", [
		'controller' => 'index',
		'action'     => 'prefs',
	]],
	'content' => ['all', "content/{entity}?/{action}?/{content}?", [
		'controller' => 'content',
		'action'     => 'index',
		'entity'     => null,
		'content'    => null,
	]],
	'setting' => ['all', "setting/{controller}?/{action}?/{id}?", [
		'controller' => 'setting',
		'action'     => 'index',
		'id'  	  	 => null,
	]],
	'widget' => ['all', "widget/{action}/{entity}", [
		'controller' => 'widget',
	]],
	'structure' => ['all', "structure/{action}?/{structure}?", [
		'controller' => 'structure',
		'action'     => 'index',
		'structure'	 => null,
	]],
	'structure_node' => ['all', "structure/node/{structure}/{action}?/{node}?", [
		'controller' => 'structure_node',
		'action'     => 'index',
		'node'    	 => null,
	]],
];

return new Router([
    'target' => $app->controller,
    'routes' => $routes,
]);