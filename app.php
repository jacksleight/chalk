<?php
use Coast\App,
	Coast\App\SubApp,
	Coast\Config;

/* Initialize */

chdir(__DIR__);
require 'vendor/autoload.php';
$config = new Config('config/config.php');
session_name('session');

$app = new App();
$app->add('ayre', \Coast\import('ayre/app.php', [
	'base'	 => 'admin',
	'config' => $config,
]));