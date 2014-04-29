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

$app = new Ayre(['path' => $path], $config->envs);
$app->set('master', 	$master)
	->set('config',		$config)
	->set('dir',		new Dir(__DIR__))
	->set('dirPath',	$app->dir->toRelative(new Path(getcwd())))
	->set('memcached',	$app->import("{$app->dir}/init/memcached.php"))
	->set('em',			$app->import("{$app->dir}/init/doctrine.php"))
	->set('swift',		$app->import("{$app->dir}/init/swift.php"))
	->set('mimeTypes',	$app->import("{$app->dir}/init/mime-types.php"))
	->set('view', new App\View([
		'dir' => "{$app->dir}/views",
	]))
	->add('image', new \Toast\App\Image([
		'dir'			=> 'data/store/files',
		'outputDir'		=> 'data/cache/images',
		'transforms'	=> $app->import("{$app->dir}/init/transforms.php")
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

$app->import("{$app->dir}/init/routes.php");

return $app;