<?php
use Doctrine\ORM\Configuration,
	Doctrine\Common\Cache\ArrayCache,
	Doctrine\Common\Cache\MemcachedCache,
	Doctrine\Common\EventManager,
	Doctrine\ORM\EntityManager,
	Chalk\Doctrine\ORM\EntityManager as ChalkEntityManager,
	Chalk\Listener as Listener,
	Chalk\Core\File\Listener as FileListener,
	Chalk\Core\Structure\Node\Listener as NodeListener,
	Chalk\Behaviour\Loggable\Listener as LoggableListener,
	Chalk\Behaviour\Searchable\Listener as SearchableListener,
	Chalk\Behaviour\Trackable\Listener as TrackableListener,
	Chalk\Behaviour\Versionable\Listener as VersionableListener;

\Coast\Doctrine\register_dbal_types();

$config = new Configuration();
$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver());
$config->setProxyDir($app->root->dir('data/proxies'));
$config->setProxyNamespace('Chalk\Proxy');
$config->setAutoGenerateProxyClasses(true);
// $config->setSQLLogger(new \Chalk\ConsoleSQLLogger());
// $config->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());

$cache = null;
if ($app->isDevelopment()) {
	$cache = new ArrayCache();
} else if (isset($app->memcached)) {
	$cache = new MemcachedCache();
	$cache->setMemcached($app->memcached);
}
if (isset($cache)) {
	$config->setQueryCacheImpl($cache);
	$config->setResultCacheImpl($cache);
	$config->setMetadataCacheImpl($cache);
}

$evm = new EventManager();
$evm->addEventSubscriber(new Listener());
$evm->addEventSubscriber(new FileListener());
$evm->addEventSubscriber(new NodeListener());
// $evm->addEventSubscriber(new LoggableListener());
$evm->addEventSubscriber(new SearchableListener());
$evm->addEventSubscriber(new VersionableListener());
$evm->addEventSubscriber($trackable = new TrackableListener());

global $em;
$em = new ChalkEntityManager(EntityManager::create(
	$app->config->database + [
		'driver'  => 'pdo_mysql',
		'charset' => 'utf8'
	],
	$config,
	$evm
));
$em->trackable($trackable);
$em->getConnection()->exec("SET NAMES utf8");

Toast\Wrapper\Collection::$em = $em;

return $em;