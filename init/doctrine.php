<?php
use Doctrine\ORM\Configuration,
	Doctrine\Common\Cache\ArrayCache,
	Doctrine\Common\Cache\MemcachedCache,
	Doctrine\Common\EventManager,
	Doctrine\ORM\EntityManager,
	Ayre\Doctrine\ORM\EntityManager as AyreEntityManager,
	Ayre\Listener as Listener,
	Ayre\Core\File\Listener as FileListener,
	Ayre\Core\Structure\Node\Listener as NodeListener,
	Ayre\Behaviour\Loggable\Listener as LoggableListener,
	Ayre\Behaviour\Searchable\Listener as SearchableListener,
	Ayre\Behaviour\Trackable\Listener as TrackableListener,
	Ayre\Behaviour\Versionable\Listener as VersionableListener;

\Coast\Doctrine\register_dbal_types();

$paths = [];
foreach ($app->modules() as $name => $module) {
	$paths[] = $module->libDir()->name();
}
$config = new Configuration();
$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver($paths));
$config->setProxyDir($app->root->dir('data/proxies'));
$config->setProxyNamespace('Ayre\Proxy');
$config->setAutoGenerateProxyClasses(true);
// $config->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());

if ($app->isDevelopment()) {
	$cache = new ArrayCache();
} else if (isset($app->memcached)) {
	$cache = new MemcachedCache();
	$cache->setMemcached($app->memcached);
}
$config->setQueryCacheImpl($cache);
$config->setResultCacheImpl($cache);
$config->setMetadataCacheImpl($cache);

$evm = new EventManager();
$evm->addEventSubscriber(new Listener());
$evm->addEventSubscriber(new FileListener());
$evm->addEventSubscriber(new NodeListener());
// $evm->addEventSubscriber(new LoggableListener());
$evm->addEventSubscriber(new SearchableListener());
$evm->addEventSubscriber(new VersionableListener());
$evm->addEventSubscriber($trackable = new TrackableListener());

global $em;
$em = new AyreEntityManager(EntityManager::create(
	$app->config->root->database + [
		'driver'  => 'pdo_mysql',
		'charset' => 'utf8'
	],
	$config,
	$evm
));
$em->trackable($trackable);
$em->getConnection()->exec("SET NAMES utf8");
$em->getMetadataFactory()->getAllMetadata();

Toast\Wrapper\Collection::$em = $em;

return $em;