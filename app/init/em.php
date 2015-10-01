<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

use Doctrine\ORM\Configuration,
	Doctrine\Common\EventManager,
	Doctrine\ORM\EntityManager,
	Doctrine\ORM\Proxy\Autoloader,
	Chalk\Doctrine\ORM\EntityManager as ChalkEntityManager,
	Chalk\Doctrine\NamingStrategy as NamingStrategy,
	Chalk\Listener as Listener,
	Chalk\Core\File\Listener as FileListener,
	Chalk\Core\Structure\Node\Listener as NodeListener,
	Chalk\Behaviour\Publishable\Listener as PublishableListener,
	Chalk\Behaviour\Loggable\Listener as LoggableListener,
	Chalk\Behaviour\Searchable\Listener as SearchableListener,
	Chalk\Behaviour\Trackable\Listener as TrackableListener,
	Chalk\Behaviour\Versionable\Listener as VersionableListener;

\Coast\Doctrine\register_dbal_types();

if (!isset($app->config->database)) {
	throw new \Chalk\Exception('Database connection details are required');
}

$config = new Configuration();
$config->setNamingStrategy(new NamingStrategy());
$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver());
$config->setProxyDir($app->config->dataDir->dir('proxy'));
$config->setProxyNamespace('Chalk\Proxy');
$config->setAutoGenerateProxyClasses(false);
$config->setQueryCacheImpl($app->cache->value());
$config->setResultCacheImpl($app->cache->value());
$config->setMetadataCacheImpl($app->cache->value());
Autoloader::register($app->config->dataDir->dir('proxy'), 'Chalk\Proxy');

$evm = new EventManager();
$evm->addEventSubscriber(new Listener());
$evm->addEventSubscriber(new FileListener());
$evm->addEventSubscriber(new NodeListener());
$evm->addEventSubscriber(new PublishableListener());
// $evm->addEventSubscriber(new LoggableListener());
$evm->addEventSubscriber(new SearchableListener());
$evm->addEventSubscriber(new VersionableListener());
$evm->addEventSubscriber($trackable = new TrackableListener());

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

Toast\Wrapper::$em = $em;

return $em;