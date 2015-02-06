<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

$app->router
	->all('index', "{controller}?/{action}?/{id}?", [
		'controller' => 'index',
		'action'     => 'index',
		'id'    	 => null,
	])
	->all('about', "about", [
		'controller' => 'index',
		'action'     => 'about',
	])
	->all('sandbox', "sandbox", [
		'controller' => 'index',
		'action'     => 'sandbox',
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