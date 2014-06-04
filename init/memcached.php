<?php
$memcached = new \Memcached();
$memcached->addServer($app->config->root->memcached['host'], $app->config->root->memcached['port']);

return $memcached;