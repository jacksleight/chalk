<?php
use Coast\App\Controller, 
	Coast\App\Request, 
	Coast\App\Response, 
	Coast\App\Router, 
	Coast\App\UrlResolver,
	Coast\App\ViewRenderer,
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

$app = (new Ayre(__DIR__, $config->envs))
	->path(new Path("{$path}"));

$router = new Router(
	new Controller('Ayre\Controller'));

$viewRenderer = new ViewRenderer(
	$app->dir('views'));

$urlResolver = new UrlResolver(
	new Url("{$config->baseUrl}{$app->path()}/"),
	$app->dir(),
	$router);

$rootUrlResolver = new UrlResolver(
	new Url("{$config->baseUrl}"),
	$root->dir());

$image = (new Image(
	$root->dir('public/data/file'),
	$root->dir('public/data/image', true),
	$urlResolver,
	$app->import($app->file('init/transforms.php'))))
	->outputUrlResolver($rootUrlResolver);

$app->set('root', 		$root)
	->set('config',		$config)
	->set('memcached',	$app->import($app->file('init/memcached.php')))
	->set('em',			$app->import($app->file('init/doctrine.php')))
	->set('swift',		$app->import($app->file('init/swift.php')))
	->set('mimeTypes',	$app->import($app->file('init/mime-types.php')))
	->set('view', $viewRenderer)
	->add('image', $image)
	->add('locale', new Locale([
		'cookie'  => 'locale',
		'locales' => [
			'en-GB' => 'en-GB@timezone=Europe/London;currency=GBP',
		]]))
	->add('router', $router)
	->set('url', $urlResolver)
	->set('rootUrl', $rootUrlResolver)
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

$app->em->uploadable()->setDefaultPath($root->dir('public/data/file', true)->name());

$user = $app->em('Ayre\Entity\User')->fetch(1);
$app->em->blameable()->setUserValue($user);

$app->import($app->file('init/routes.php'));

return $app;