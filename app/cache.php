<?php
use Doctrine\Common\Cache\ArrayCache,
	Doctrine\Common\Cache\MemcacheCache,
	Doctrine\Common\Cache\MemcachedCache,
	Doctrine\Common\Cache\RedisCache,
	Doctrine\Common\Cache\XcacheCache;

if ($app->isDevelopment() || !isset($app->config->cache)) {
	return new ArrayCache();
}

$config = $app->config->cache;
if ($config['driver'] == 'memcache' && extension_loaded('memcache')) {
	$memcache = new Memcache();
	$memcache->connect($config['host'], $config['port']);
	$cache = new MemcacheCache();
	$cache->setMemcache($memcache);
	return $cache;
}
if ($config['driver'] == 'memcached' && extension_loaded('memcached')) {
	$memcached = new Memcached();
	$memcached->addServer($config['host'], $config['port']);
	$cache = new MemcachedCache();
	$cache->setMemcached($memcached);
	return $cache;
}
if ($config['driver'] == 'redis' && extension_loaded('redis')) {
	$redis = new Redis();
	$redis->connect($config['host'], $config['port']);
	$cache = new RedisCache();
	$cache->setRedis($redis);
	return $cache;
}
if ($config['driver'] == 'xcache' && extension_loaded('xcache')) {
	$cache = new XcacheCache();
	return $cache;
}
return new ArrayCache();