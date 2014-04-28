<?php
use Coast\App, 
	Coast\App\Request, 
	Coast\App\Response, 
	Coast\Config,
	Coast\Path,
	Coast\Url;

/* Initialize */

define('AYRE_DIR', __DIR__);

$app = new Ayre($config->envs);
$app->set('config', $config)
	->set('memcached', $app->import(AYRE_DIR . '/init/memcached.php'))
	->set('em', $app->import(AYRE_DIR . '/init/doctrine.php'))
	->set('swift', $app->import(AYRE_DIR . '/init/swift.php'))
	->set('mimeTypes', $app->import(AYRE_DIR . '/init/mime-types.php'))
	->set('view', new App\View([
		'dir' => AYRE_DIR . '/views',
	]))
	->add('image', new \Toast\App\Image([
		'dir'			=> 'data/store/files',
		'outputDir'		=> 'data/cache/images',
		'transforms'	=> $app->import(AYRE_DIR . '/init/transforms.php')
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
	->set('url', new App\Url($config->url + [
		'base'    => '/admin/',
		'dir'     => AYRE_DIR,
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

$user = $app->em('Ayre\Entity\User')->fetch(1);
$app->user($user);

/* Routes */

$app->router
	->all('index', '{controller}?/{action}?/{id}?', [
		'controller' => 'index',
		'action'     => 'index',
		'id'    	 => null,
	])
	->all('content', 'content/{entityType}?/{action}?/{id}?', [
		'controller' => 'content',
		'action'     => 'index',
		'id'    	 => null,
	])
	->all('structure', 'structure/{action}?/{id}?', [
		'controller' => 'structure',
		'action'     => 'index',
		'id'    	 => null,
	]);

/* Return */

return $app;