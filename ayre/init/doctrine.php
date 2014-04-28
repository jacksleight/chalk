<?php
$config = new \Doctrine\ORM\Configuration();
$config->setProxyDir('data/proxies');
$config->setProxyNamespace('Ayre\Proxy');
$config->setAutoGenerateProxyClasses(true);

if ($app->isDebug()) {
	$cache = new \Doctrine\Common\Cache\ArrayCache();
} else if (isset($app->memcached)) {
	$cache = new \Doctrine\Common\Cache\MemcachedCache();
	$cache->setMemcached($app->memcached);
	$config->setQueryCacheImpl($cache);
	$config->setResultCacheImpl($cache);
	$config->setMetadataCacheImpl($cache);
}

\Coast\Doctrine\register_dbal_types();

\Doctrine\Common\Annotations\AnnotationRegistry::registerFile('vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');
$reader			= new \Doctrine\Common\Annotations\AnnotationReader();
$cachedReader	= new \Doctrine\Common\Annotations\CachedReader($reader, $cache, $app->isDebug());
$chain			= new \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain();
$driver			= new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($cachedReader, [AYRE_DIR . '/lib/Ayre/Entity']);
$chain->addDriver($driver, 'Ayre\Entity');
\Gedmo\DoctrineExtensions::registerAbstractMappingIntoDriverChainORM($chain, $cachedReader);
$config->setMetadataDriverImpl($chain);

$evm = new \Doctrine\Common\EventManager();
$classes = [
	'Ayre\Listener',
	'Ayre\Behaviour\Loggable\Listener',
	'Ayre\Behaviour\Searchable\Listener',
	'Ayre\Behaviour\Versionable\Listener',
	'Gedmo\Blameable\BlameableListener',
	'Gedmo\Sluggable\SluggableListener',
	'Gedmo\Timestampable\TimestampableListener',
	'Gedmo\Tree\TreeListener',
	'Gedmo\Uploadable\UploadableListener',
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
$em->Uploadable($listeners['Gedmo\Uploadable\UploadableListener']);
$em->getConnection()->exec("SET NAMES utf8");
$em->getMetadataFactory()->getAllMetadata();

return $em;