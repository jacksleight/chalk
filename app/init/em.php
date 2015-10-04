<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

use Doctrine\ORM\Configuration;
use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Proxy\Autoloader;
use Chalk\Doctrine\ORM\EntityManager as ChalkEntityManager;
use Chalk\Doctrine\NamingStrategy as NamingStrategy;
use Chalk\Listener as Listener;

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

$em = new ChalkEntityManager(EntityManager::create(
	$app->config->database + [
		'driver'  => 'pdo_mysql',
		'charset' => 'utf8'
	],
	$config,
	$evm
));
$em->getConnection()->exec("SET NAMES utf8");

Toast\Wrapper::$em = $em;

return $em;