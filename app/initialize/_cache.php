<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\PhpFileCache;

if ($app->isDevelopment()) {
	$cache = new ArrayCache();
} else {
	$cache = new PhpFileCache($app->config->dataDir->dir('cache'));
}
$cache->setNamespace($app->config->cacheNamespace);
return $cache;