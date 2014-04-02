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
$annotationReader		= new \Doctrine\Common\Annotations\AnnotationReader();
$cachedAnnotationReader	= new \Doctrine\Common\Annotations\CachedReader($annotationReader, $cache, $app->isDebug());
$driverChain			= new \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain();
$annotationDriver		= new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($cachedAnnotationReader, array('lib'));
$driverChain->addDriver($annotationDriver, 'Ayre');
Gedmo\DoctrineExtensions::registerAbstractMappingIntoDriverChainORM($driverChain, $cachedAnnotationReader);
$doct->setMetadataDriverImpl($driverChain);

$evm = new \Doctrine\Common\EventManager();
$actionListener = new \Ayre\Listener\Action($evm);
$searchListener = new \Ayre\Listener\Search($evm);
$evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, new \Ayre\Doctrine\Event\LoadClassMetadata());

$sluggable = new Gedmo\Sluggable\SluggableListener();
$sluggable->setAnnotationReader($cachedAnnotationReader);
$evm->addEventSubscriber($sluggable);

$timestampable = new Gedmo\Timestampable\TimestampableListener();
$timestampable->setAnnotationReader($cachedAnnotationReader);
$evm->addEventSubscriber($timestampable);

global $blameable;
$blameable = new \Gedmo\Blameable\BlameableListener();
$blameable->setAnnotationReader($cachedAnnotationReader);
$evm->addEventSubscriber($blameable);

$uploadable = new Gedmo\Uploadable\UploadableListener();
$uploadable->setAnnotationReader($cachedAnnotationReader);
$evm->addEventSubscriber($uploadable);

$tree = new \Gedmo\Tree\TreeListener();
$tree->setAnnotationReader($cachedAnnotationReader);
$evm->addEventSubscriber($tree);

$em = Doctrine\ORM\EntityManager::create(array_merge($app->config->database, ['charset' => 'utf8']), $doct, $evm);
$em->getConnection()->exec("SET NAMES utf8");

return $em;