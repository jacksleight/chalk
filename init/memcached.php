<?php
if (!extension_loaded('memcached')) {
	return;
}

$memcached = new \Memcached();
$memcached->addServer($app->config->memcached['host'], $app->config->memcached['port']);

return $memcached;