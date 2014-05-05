<?php
use Coast\App,
	Coast\App\SubApp,
	Coast\Config;

session_name('session');
date_default_timezone_set('UTC');
require __DIR__ . '/vendor/autoload.php';
$config = new Config(__DIR__ . '/config/config.php');

$app = new App(__DIR__);
$app->add('ayre', $app->import($app->file('ayre/app.php'), [
		'path'	 => 'admin',
		'config' => $config,
	]))
	->set('view', new App\ViewRenderer($app->dir('views')))
	->add('frontend', $app->ayre->frontend());