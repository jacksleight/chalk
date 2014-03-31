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

$app = new \Ayre($config->envs);
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
	->all('index', '{controller}?/{action}?/{id}?', [
		'controller' => 'index',
		'action'     => 'index',
		'id'    	 => null,
	]);

/* Memcached */

$memcached = new \Memcached();
$memcached->addServer($config->memcached['host'], $config->memcached['port']);
$app->set('memcached', $memcached);

/* Doctrine */

$doct = new Doctrine\ORM\Configuration();
\Doctrine\DBAL\Types\Type::addType('json', '\JS\Doctrine\DBAL\Types\JSONType');
\Doctrine\DBAL\Types\Type::addType('url', '\JS\Doctrine\DBAL\Types\URLType');
$doct->addCustomStringFunction('CEILING', '\JS\Doctrine\ORM\Query\Mysql\Ceiling');
$doct->addCustomStringFunction('FIELD', '\JS\Doctrine\ORM\Query\Mysql\Field');
$doct->addCustomStringFunction('FLOOR', '\JS\Doctrine\ORM\Query\Mysql\Floor');
$doct->addCustomStringFunction('IF', '\JS\Doctrine\ORM\Query\Mysql\IfElse');
$doct->addCustomStringFunction('ROUND', '\JS\Doctrine\ORM\Query\Mysql\Round');
$doct->addCustomStringFunction('UTC_DATE', '\JS\Doctrine\ORM\Query\Mysql\UtcDate');
$doct->addCustomStringFunction('UTC_TIME', '\JS\Doctrine\ORM\Query\Mysql\UtcTime');
$doct->addCustomStringFunction('UTC_TIMESTAMP', '\JS\Doctrine\ORM\Query\Mysql\UtcTimestamp');
$doct->setMetadataDriverImpl(new \Js\Doctrine\Orm\Mapping\Driver\StaticPhpDriver(null));
$doct->setProxyDir('data/proxy');
$doct->setProxyNamespace('Ayre\Proxy');
$cache = new Doctrine\Common\Cache\MemcachedCache();
$cache->setMemcached($memcached);
$doct->setQueryCacheImpl($cache);
$doct->setResultCacheImpl($cache);
$doct->setMetadataCacheImpl($cache);
$em = Doctrine\ORM\EntityManager::create(array_merge($config->database, [
	'charset' => 'utf8',
]), $doct);
$em->getConnection()->exec("SET NAMES utf8");
$app->set('em', $em);

/* Swift Mailer */

$swift = Swift_Mailer::newInstance(Swift_MailTransport::newInstance());
$app->set('swift', $swift);