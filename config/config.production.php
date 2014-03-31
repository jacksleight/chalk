<?php
return [
	'envs' => [
		'SERVER'	=> 'production',
		'DEBUG'		=> false,
	],
	'database' => [
		'driver'	=> 'pdo_mysql',
		'host'		=> 'localhost',
		'user'		=> '',
		'password'	=> '',
		'dbname'	=> '',
	],
	'memcached' => [
		'host'		=> 'localhost',
		'port'		=> 11211,
	],
	'url' => [
		'base' 		=> '/',
	],
];