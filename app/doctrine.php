<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

use Doctrine\ORM\Configuration,
	Doctrine\Common\EventManager,
	Doctrine\ORM\EntityManager,
	Chalk\Doctrine\ORM\EntityManager as ChalkEntityManager,
	Chalk\Doctrine\NamingStrategy as NamingStrategy,
	Chalk\Listener as Listener,
	Chalk\Core\File\Listener as FileListener,
	Chalk\Core\Structure\Node\Listener as NodeListener,
	Chalk\Behaviour\Loggable\Listener as LoggableListener,
	Chalk\Behaviour\Searchable\Listener as SearchableListener,
	Chalk\Behaviour\Trackable\Listener as TrackableListener,
	Chalk\Behaviour\Versionable\Listener as VersionableListener;

\Coast\Doctrine\register_dbal_types();

$config = new Configuration();
$config->setNamingStrategy(new NamingStrategy());
$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver());
$config->setProxyDir($app->root->dir('data/proxies'));
$config->setProxyNamespace('Chalk\Proxy');
$config->setAutoGenerateProxyClasses(true);
$config->setQueryCacheImpl($app->cache);
$config->setResultCacheImpl($app->cache);
$config->setMetadataCacheImpl($app->cache);
// $config->setSQLLogger(new \Chalk\ConsoleSQLLogger());
// $config->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());

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