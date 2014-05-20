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

$options = (new \Coast\Config())
	->fromArray(isset($options) ? $options : []);
if (!isset($app)) {
	throw new \Exception('Ayre can only run as middleware');
} else if (!isset($options->path)) {
	throw new \Exception('You must specify a path');
}
$root = $app;

$app = (new Ayre(null, $config->envs))
	->path(new Path("{$options->path}"));

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
	->set('options',	$options)
	->set('memcached',	$app->import($app->file('init/memcached.php')))
	->set('em',			$app->import($app->file('init/doctrine.php')))
	->set('swift',		$app->import($app->file('init/swift.php')))
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

\Ayre\Core\File::baseDir($root->dir('public/data/file', true));
\Ayre\Core\File::mimeTypes($app->import($app->file('init/mime-types.php')));

$app->import($app->file('init/routes.php'));

return $app;