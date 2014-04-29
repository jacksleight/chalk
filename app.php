<?php
use Coast\App,
	Coast\App\SubApp,
	Coast\Config;

chdir(__DIR__);
session_name('session');
require 'vendor/autoload.php';
$config = new Config('config/config.php');

$app = new App();
$app->add('ayre', $app->import('ayre/app.php', [
	'path'	 => 'admin',
	'config' => $config,
]));