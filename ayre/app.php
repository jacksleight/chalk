<?php
use Coast\App\Controller, 
	Coast\App\Request, 
	Coast\App\Response, 
	Coast\App\Router, 
	Coast\App\View,
	Coast\Config,
	Coast\Path,
	Coast\Url,
	Toast\App\Image,
	Toast\App\Locale;

if (!isset($app)) {
	throw new \Exception('Ayre can only run as middleware');
} else if (!isset($path)) {
	throw new \Exception('You must specify a path');
}
$root = $app;

$app = new Ayre(__DIR__, $config->envs);
$app->path(new Path("{$path}"))
	->set('root', 		$root)
	->set('config',		$config)
	->set('memcached',	$app->import($app->file('init/memcached.php')))
	->set('em',			$app->import($app->file('init/doctrine.php')))
	->set('swift',		$app->import($app->file('init/swift.php')))
	->set('mimeTypes',	$app->import($app->file('init/mime-types.php')))
	->set('view', new View(
		$app->dir('views')))
	->add('image', new Image(
		$root->dir(),
		$root->dir('public/data/cache/images'),
		$app->import($app->file('init/transforms.php'))))
	->add('locale', new Locale([
		'cookie'  => 'locale',
		'locales' => [
			'en-GB' => 'en-GB@timezone=Europe/London;currency=GBP',
		]]))
	->add('router', new Router(
		new Controller('Ayre\Controller')))
	->set('url', new Coast\App\Url(
		new Url("{$config->baseUrl}{$app->path()}/"),
		$app->dir(),
		$app->router))
	->set('rootUrl', new Coast\App\Url(
		new Url("{$config->baseUrl}"),
		$root->dir()))
	->notFoundHandler(function(Request $req, Response $res) {
		return $res
			->status(404)
			->html($this->view->render('error/not-found'));
	});

if (!$app->isDebug()) {
	$app->errorHandler(function(Request $req, Response $res, Exception $e) {
		return $res
			->status(500)
			->html($this->view->render('error/index', array('e' => $e)));
	});
}

$user = $app->em('Ayre\Entity\User')->fetch(1);
$app->user($user);

$app->import($app->file('init/routes.php'));

return $app;