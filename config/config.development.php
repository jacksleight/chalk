<?php
return [
	'envs' => [
		'SERVER'	=> 'development',
		'DEBUG'		=> true,
	],
	'database' => [
		'driver'	=> 'pdo_mysql',
		'host'		=> 'localhost',
		'user'		=> 'root',
		'password'	=> 'password',
		'dbname'	=> '_ayre',
	],
	'memcached' => [
		'host'		=> 'localhost',
		'port'		=> 11211,
	],
	'url' => [
		'base' 		=> '/_ayre/',
	],
];