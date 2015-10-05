<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

use Doctrine\Common\Cache\ArrayCache,
	Doctrine\Common\Cache\MemcacheCache,
	Doctrine\Common\Cache\MemcachedCache,
	Doctrine\Common\Cache\RedisCache,
	Doctrine\Common\Cache\XcacheCache;

$config = $app->config->cache;
if ($app->isDevelopment()) {
	$cache = new ArrayCache();
} else if ($config['driver'] == 'memcache' && extension_loaded('memcache')) {
	$memcache = new Memcache();
	$memcache->connect($config['host'], $config['port']);
	$cache = new MemcacheCache();
	$cache->setMemcache($memcache);
} else if ($config['driver'] == 'memcached' && extension_loaded('memcached')) {
	$memcached = new Memcached();
	$memcached->addServer($config['host'], $config['port']);
	$cache = new MemcachedCache();
	$cache->setMemcached($memcached);
} else if ($config['driver'] == 'redis' && extension_loaded('redis')) {
	$redis = new Redis();
	$redis->connect($config['host'], $config['port']);
	$cache = new RedisCache();
	$cache->setRedis($redis);
} else if ($config['driver'] == 'xcache' && extension_loaded('xcache')) {
	$cache = new XcacheCache();
} else {
	$cache = new ArrayCache();
}
$cache->setNamespace($config['namespace']);

return $cache;