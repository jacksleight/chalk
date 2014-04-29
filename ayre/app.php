<?php
use Coast\App, 
	Coast\App\Request, 
	Coast\App\Response, 
	Coast\Config,
	Coast\Path,
	Coast\Dir,
	Coast\Url;

if (!isset($app)) {
	throw new \Exception('Ayre can only run as middleware');
} else if (!isset($path)) {
	throw new \Exception('You must specify a path');
}
$master = $app;

$app = new Ayre(__DIR__, ['path' => $path], $config->envs);
$app->set('master', 	$master)
	->set('config',		$config)
	->set('dirPath',	$app->dir()->toRelative($master->dir()))
	->set('memcached',	$app->import('init/memcached.php'))
	->set('em',			$app->import('init/doctrine.php'))
	->set('swift',		$app->import('init/swift.php'))
	->set('mimeTypes',	$app->import('init/mime-types.php'))
	->set('view', new App\View([
		'dir' => 'views',
	]))
	->add('image', new \Toast\App\Image([
		'sourceDir'		=> 'public/data/store/files',
		'targetDir'		=> 'public/data/cache/images',
		'transforms'	=> $app->import($app->dir()->file('init/transforms.php'))
	]))
	->add('locale', new \Toast\App\Locale([
		'cookie'  => 'locale',
		'locales' => [
			'en-GB' => 'en-GB@timezone=Europe/London;currency=GBP',
		],
	]))
	->add('router', new App\Router([
		'target' => new App\Controller(['namespace' => 'Ayre\Controller']),
	]))
	->set('url', new App\Url([
		'base'		=> "{$config->url['base']}{$path}/",
		'dirBase'	=> "{$config->url['base']}",
		'router'	=> $app->router,
		'version'	=> function(Url $url, Path $path) {
			$url->path()->suffix("." . $path->modifyTime()->getTimestamp());
		},
	]))
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

$app->import('init/routes.php');

return $app;