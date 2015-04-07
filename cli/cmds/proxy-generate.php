<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

cli\line('Deleting proxy classes..');
if ($app->config->dataDir->dir('proxy')->exists()) {
	$app->config->dataDir->dir('proxy')->remove(true);
}

cli\line('Generating proxy classes..');
$app->em->getProxyFactory()->generateProxyClasses($app->em->getMetadataFactory()->getAllMetadata(), null);