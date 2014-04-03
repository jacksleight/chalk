<?php
$doct = new \Doctrine\ORM\Configuration();
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

$types = [
	'json'	=> 'Js\Doctrine\DBAL\Types\JSONType',
	'url'	=> 'Js\Doctrine\DBAL\Types\URLType',
];
foreach ($types as $name => $class) {
	\Doctrine\DBAL\Types\Type::addType($name, $class);
}

$functions = [
	'FIELD'			=> 'Js\Doctrine\ORM\Query\Mysql\Field',
	'FLOOR'			=> 'Js\Doctrine\ORM\Query\Mysql\Floor',
	'IF'			=> 'Js\Doctrine\ORM\Query\Mysql\IfElse',
	'ROUND'			=> 'Js\Doctrine\ORM\Query\Mysql\Round',
	'UTC_DATE'		=> 'Js\Doctrine\ORM\Query\Mysql\UtcDate',
	'UTC_TIME'		=> 'Js\Doctrine\ORM\Query\Mysql\UtcTime',
	'UTC_TIMESTAMP'	=> 'Js\Doctrine\ORM\Query\Mysql\UtcTimestamp',
];
foreach ($functions as $name => $class) {
	$doct->addCustomStringFunction($name, $class);
}

Doctrine\Common\Annotations\AnnotationRegistry::registerFile('vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');
$reader			= new \Doctrine\Common\Annotations\AnnotationReader();
$cachedReader	= new \Doctrine\Common\Annotations\CachedReader($reader, $cache, $app->isDebug());
$chain			= new \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain();
$driver			= new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($cachedReader, array('lib/Ayre/Entity'));
$chain->addDriver($driver, 'Ayre\Entity');
Gedmo\DoctrineExtensions::registerAbstractMappingIntoDriverChainORM($chain, $cachedReader);
$doct->setMetadataDriverImpl($chain);

$evm = new \Doctrine\Common\EventManager();
$classes = [
	'Ayre\Listener',
	'Ayre\Behaviour\Loggable\Listener',
	'Ayre\Behaviour\Searchable\Listener',
	'Ayre\Behaviour\Versionable\Listener',
	'Ayre\Entity\Tree\Node\Listener',
	'Gedmo\Blameable\BlameableListener',
	'Gedmo\Sluggable\SluggableListener',
	'Gedmo\Timestampable\TimestampableListener',
	'Gedmo\Tree\TreeListener',
	'Gedmo\Uploadable\UploadableListener',
];
foreach ($classes as $class) {
	$listener = new $class();
	if (method_exists($listener, 'setAnnotationReader')) {
		$listener->setAnnotationReader($cachedReader);
	}
	$evm->addEventSubscriber($listener);
	if ($class == 'Gedmo\Blameable\BlameableListener') {
		global $blameable;
		$blameable = $listener;
	}
}

$em = Doctrine\ORM\EntityManager::create(array_merge($app->config->database, ['charset' => 'utf8']), $doct, $evm);
$em->getConnection()->exec("SET NAMES utf8");

return $em;