<?php
use Coast\App, 
	Coast\App\Request, 
	Coast\App\Response, 
	Coast\Config,
	Coast\Path,
	Coast\Url;

/* Initialize */

chdir(__DIR__);
require 'vendor/autoload.php';

$config = new Config('config/config.php');
session_name('session');

$app = new Ayre($config->envs);
$app->set('config', $config)
	->set('memcached', $app->import('app_memcached.php'))
	->set('entity', new Ayre\App\Entity($app->import('app_doctrine.php')))
	->set('swift', $app->import('app_swift.php'))
	->set('mimeTypes', $app->import('app_mime-types.php'))
	->set('html', new \Js\App\Html())
	->set('image', new \Js\App\Image())
//  ->set('message', new App\Message())
//  ->set('oembed', new App\Oembed($config->oembed))
	->set('view', new App\View([
		'dir' => 'views',
	]))
	->add('locale', new \Js\App\Locale([
		'cookie'  => 'locale',
		'locales' => [
			'en-GB' => 'en-GB@timezone=Europe/London;currency=GBP',
		],
	]))
	->add('router', new App\Router([
		'target' => new App\Controller(['namespace' => 'Ayre\Controller']),
	]))
	->set('url', new App\Url($config->url + [
		'dir'     => 'public',
		'router'  => $app->router,
		'version' => function(Url $url, Path $path) {
			$url->path()->suffix(".{$path->modifyTime()->getTimestamp()}");
		},
	]))
	->notFoundHandler(function(Request $req, Response $res) {
		$res->status(404)
			->html($this->view->render('/error/not-found'));
	});
if (!$app->isDebug()) {
	$app->errorHandler(function(Request $req, Response $res, Exception $e) {
		$res->status(500)
			->html($this->view->render('/error/index', array('e' => $e)));
	});
}

/* Routes */

$app->router
	->all('index', '{controller}?/{action}?/{id}?', [
		'controller' => 'index',
		'action'     => 'index',
		'id'    	 => null,
	])
	->all('entity', '{controller}/{type}/{action}?/{id}?', [
		'action'     => 'index',
		'id'    	 => null,
	]);