<?php
$doct = new \Doctrine\ORM\Configuration();

\Doctrine\DBAL\Types\Type::addType('json', '\Js\Doctrine\DBAL\Types\JSONType');
\Doctrine\DBAL\Types\Type::addType('url', '\Js\Doctrine\DBAL\Types\URLType');
$doct->addCustomStringFunction('CEILING', '\Js\Doctrine\ORM\Query\Mysql\Ceiling');
$doct->addCustomStringFunction('FIELD', '\Js\Doctrine\ORM\Query\Mysql\Field');
$doct->addCustomStringFunction('FLOOR', '\Js\Doctrine\ORM\Query\Mysql\Floor');
$doct->addCustomStringFunction('IF', '\Js\Doctrine\ORM\Query\Mysql\IfElse');
$doct->addCustomStringFunction('ROUND', '\Js\Doctrine\ORM\Query\Mysql\Round');
$doct->addCustomStringFunction('UTC_DATE', '\Js\Doctrine\ORM\Query\Mysql\UtcDate');
$doct->addCustomStringFunction('UTC_TIME', '\Js\Doctrine\ORM\Query\Mysql\UtcTime');
$doct->addCustomStringFunction('UTC_TIMESTAMP', '\Js\Doctrine\ORM\Query\Mysql\UtcTimestamp');

$doct->setProxyDir('data/proxy');
$doct->setProxyNamespace('Ayre\Proxy');
$doct->setAutoGenerateProxyClasses(!$app->isDebug());

if ($app->isDebug()) {
	$cache = new \Doctrine\Common\Cache\ArrayCache();
} else if (isset($app->memcached)) {
	$cache = new \Doctrine\Common\Cache\MemcachedCache();
	$cache->setMemcached($app->memcached);
	$doct->setQueryCacheImpl($cache);
	$doct->setResultCacheImpl($cache);
	$doct->setMetadataCacheImpl($cache);
}

Doctrine\Common\Annotations\AnnotationRegistry::registerFile(
    'vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
);
$reader			= new \Doctrine\Common\Annotations\AnnotationReader();
$cachedReader	= new \Doctrine\Common\Annotations\CachedReader($reader, $cache, $app->isDebug());
$chain			= new \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain();
$driver			= new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($cachedReader, array('lib'));
$chain->addDriver($driver, 'Ayre');
Gedmo\DoctrineExtensions::registerAbstractMappingIntoDriverChainORM($chain, $cachedReader);
$doct->setMetadataDriverImpl($chain);

$evm = new \Doctrine\Common\EventManager();
$evm->addEventListener([
	\Doctrine\ORM\Events::onFlush,
	\Doctrine\ORM\Events::postFlush,
], new \Ayre\Listener\Node());
$evm->addEventListener(\Doctrine\ORM\Events::onFlush, new \Ayre\Listener\Search());
$evm->addEventListener(\Doctrine\ORM\Events::onFlush, new \Ayre\Listener\Action());
$evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, new \Ayre\Listener\Metadata());

$sluggable = new Gedmo\Sluggable\SluggableListener();
$sluggable->setAnnotationReader($cachedReader);
$evm->addEventSubscriber($sluggable);

$timestampable = new Gedmo\Timestampable\TimestampableListener();
$timestampable->setAnnotationReader($cachedReader);
$evm->addEventSubscriber($timestampable);

global $blameable;
$blameable = new \Gedmo\Blameable\BlameableListener();
$blameable->setAnnotationReader($cachedReader);
$evm->addEventSubscriber($blameable);

$uploadable = new Gedmo\Uploadable\UploadableListener();
$uploadable->setAnnotationReader($cachedReader);
$evm->addEventSubscriber($uploadable);

$tree = new \Gedmo\Tree\TreeListener();
$tree->setAnnotationReader($cachedReader);
$evm->addEventSubscriber($tree);

$em = Doctrine\ORM\EntityManager::create(array_merge($app->config->database, ['charset' => 'utf8']), $doct, $evm);
$em->getConnection()->exec("SET NAMES utf8");

return $em;