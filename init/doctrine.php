<?php
$config = new \Doctrine\ORM\Configuration();
$config->setProxyDir('data/proxies');
$config->setProxyNamespace('Ayre\Proxy');
$config->setAutoGenerateProxyClasses(true);

if ($app->isDevelopment()) {
	$cache = new \Doctrine\Common\Cache\ArrayCache();
} else if (isset($app->memcached)) {
	$cache = new \Doctrine\Common\Cache\MemcachedCache();
	$cache->setMemcached($app->memcached);
}
$config->setQueryCacheImpl($cache);
$config->setResultCacheImpl($cache);
$config->setMetadataCacheImpl($cache);

\Coast\Doctrine\register_dbal_types();

\Doctrine\Common\Annotations\AnnotationRegistry::registerFile($app->root->file('vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php')->toString());
$reader			= new \Doctrine\Common\Annotations\AnnotationReader();
$cachedReader	= new \Doctrine\Common\Annotations\CachedReader($reader, $cache, $app->isDevelopment());
$chain			= new \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain();
$driver			= new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($cachedReader, [$app->dir('lib/Ayre/Entity')->toString()]);
$chain->addDriver($driver, 'Ayre\Entity');
\Gedmo\DoctrineExtensions::registerAbstractMappingIntoDriverChainORM($chain, $cachedReader);
$config->setMetadataDriverImpl($chain);

$evm = new \Doctrine\Common\EventManager();
$classes = [
	'Ayre\Listener',
	'Ayre\Entity\File\Listener',
	'Ayre\Entity\Structure\Node\Listener',
	'Ayre\Behaviour\Loggable\Listener',
	'Ayre\Behaviour\Searchable\Listener',
	'Ayre\Behaviour\Versionable\Listener',
	'Gedmo\Blameable\BlameableListener',
	'Gedmo\Timestampable\TimestampableListener',
	'Gedmo\Tree\TreeListener',
];
$listeners = [];
foreach ($classes as $class) {
	$listener = new $class();
	if (method_exists($listener, 'setAnnotationReader')) {
		$listener->setAnnotationReader($cachedReader);
	}
	$evm->addEventSubscriber($listener);
	$listeners[$class] = $listener;
}

// $config->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());

global $em;
$em = new \Ayre\Doctrine\ORM\EntityManager(\Doctrine\ORM\EntityManager::create(
	$app->config->database + [
		'driver'	=> 'pdo_mysql',
		'charset'	=> 'utf8'
	],
	$config,
	$evm
));
$em->blameable($listeners['Gedmo\Blameable\BlameableListener']);
$em->getConnection()->exec("SET NAMES utf8");
$em->getMetadataFactory()->getAllMetadata();

Toast\Wrapper\Collection::$em = $em;
return $em;