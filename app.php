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

$app = new \App($config->envs);
$app->set('config', $config)
	->set('entity', new \Js\App\Entity())
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
		'target' => new App\Controller(['namespace' => 'App\Controller']),
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
	->all('index', '{path}?', [
		'controller' => 'index',
		'action'     => 'index',
	]);

/* Memcached */

// $memcached = new Memcached();
// $memcached->addServer($config->memcached['host'], $config->memcached['port']);
// $app->set('memcached', $memcached);

/* Doctrine */

// $doct = new Doctrine\ORM\Configuration();
// \Coast\doctrine_configure($doct);
// $doct->setMetadataDriverImpl(new \Js\Doctrine\Orm\Mapping\Driver\StaticPhpDriver(null));
// $doct->setProxyDir('data/proxy');
// $doct->setProxyNamespace('App\Proxy');
// $cache = new Doctrine\Common\Cache\MemcachedCache();
// $cache->setMemcached($memcached);
// $doct->setQueryCacheImpl($cache);
// $doct->setResultCacheImpl($cache);
// $doct->setMetadataCacheImpl($cache);
// if ($app->isDebug('sql')) {
//  $doct->setSQLLogger(new Doctrine\DBAL\Logging\EchoSQLLogger());
// }
// $em = Doctrine\ORM\EntityManager::create(array_merge($config->database, [
//  'charset' => 'utf8',
// ]), $doct);
// $em->getConnection()->exec("SET NAMES utf8");
// $app->set('em', $em);

/* Swift Mailer */

$swift = Swift_Mailer::newInstance(Swift_MailTransport::newInstance());
$app->set('swift', $swift);