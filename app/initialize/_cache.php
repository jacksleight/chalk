<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\MemcacheCache;
use Doctrine\Common\Cache\MemcachedCache;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\Common\Cache\XcacheCache;
use Doctrine\Common\Cache\FileCache;

$config = $app->config->cache;
if ($app->isDevelopment()) {
	$cache = new ArrayCache();
} else if ($config['driver'] == 'memcache') {
	$memcache = new Memcache();
	$memcache->connect($config['host'], $config['port']);
	$cache = new MemcacheCache();
	$cache->setMemcache($memcache);
} else if ($config['driver'] == 'memcached') {
	$memcached = new Memcached();
	$memcached->addServer($config['host'], $config['port']);
	$cache = new MemcachedCache();
	$cache->setMemcached($memcached);
} else if ($config['driver'] == 'redis') {
	$redis = new Redis();
	$redis->connect($config['host'], $config['port']);
	$cache = new RedisCache();
	$cache->setRedis($redis);
} else {
	$class = 'Doctrine\\Common\\Cache\\' . ucfirst($config['driver']) . 'Cache';
	if (is_a($class, 'Doctrine\\Common\\Cache\\FileCache', true)) {
		$cache = new $class($app->config->dataDir->dir('cache'));
	} else {
		$cache = new $class();
	}
}
$cache->setNamespace($config['namespace']);

return $cache;