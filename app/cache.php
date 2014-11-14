<?php
use Doctrine\Common\Cache\ArrayCache,
	Doctrine\Common\Cache\MemcachedCache;

if ($app->isDevelopment()) {
	$cache = new ArrayCache();
} else {
	if (isset($app->config->memcached) && extension_loaded('memcached')) {
		$memcached = new Memcached();
		$memcached->addServer($app->config->memcached['host'], $app->config->memcached['port']);
		$cache = new MemcachedCache();
		$cache->setMemcached($memcached);
	} else {
		$cache = new ArrayCache();
	}
}

return $cache;