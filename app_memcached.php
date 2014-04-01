<?php
$memcached = new \Memcached();
$memcached->addServer($app->config->memcached['host'], $app->config->memcached['port']);

return $memcached;