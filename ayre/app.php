<?php
use Coast\App, 
	Coast\App\Request, 
	Coast\App\Response, 
	Coast\Config,
	Coast\Path,
	Coast\Url;

/* Initialize */

define('AYRE_DIR',  __DIR__);
define('AYRE_PATH', (string) (new \Coast\Path(AYRE_DIR))->toRelative(new \Coast\Path(getcwd())));
define('AYRE_BASE', "{$base}/");

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
	->add(function(Request $req, Response $res) {
		if (strpos($req->path(), AYRE_BASE) !== 0) {
			return false;
		}
	})
	->add('router', new App\Router([
		'target' => new App\Controller(['namespace' => 'Ayre\Controller']),
	]))
	->set('url', new App\Url($config->url + [
		'base'		=> $config->url['base'] . AYRE_BASE,
		'pathBase'	=> $config->url['base'],
		'dir'		=> AYRE_DIR,
		'router'	=> $app->router,
		'version'	=> function(Url $url, Path $path) {
			$url->path()->suffix(".{$path->modifyTime()->getTimestamp()}");
		},
	]))
	->notFoundHandler(function(Request $req, Response $res) {
		if (strpos($req->path(), AYRE_BASE) !== 0) {
			return null;
		}
		return $res->status(404)
			->html($this->view->render('/error/not-found'));
	});
if (!$app->isDebug()) {
	$app->errorHandler(function(Request $req, Response $res, Exception $e) {
		return $res->status(500)
			->html($this->view->render('/error/index', array('e' => $e)));
	});
}

$user = $app->em('Ayre\Entity\User')->fetch(1);
$app->user($user);

/* Routes */

$app->router
	->all('index', AYRE_BASE . '{controller}?/{action}?/{id}?', [
		'controller' => 'index',
		'action'     => 'index',
		'id'    	 => null,
	])
	->all('content', AYRE_BASE . 'content/{entityType}?/{action}?/{id}?', [
		'controller' => 'content',
		'action'     => 'index',
		'id'    	 => null,
	])
	->all('structure', AYRE_BASE . 'structure/{action}?/{id}?', [
		'controller' => 'structure',
		'action'     => 'index',
		'id'    	 => null,
	]);

/* Return */

return $app;