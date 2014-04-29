<?php
use Coast\App,
	Coast\App\SubApp,
	Coast\Config;

session_name('session');
require __DIR__ . '/vendor/autoload.php';
$config = new Config(__DIR__ . '/config/config.php');

$app = new App(__DIR__);
$app->add('ayre', $app->import('ayre/app.php', [
	'path'	 => 'admin',
	'config' => $config,
]));