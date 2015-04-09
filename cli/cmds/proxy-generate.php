<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

use Chalk\Doctrine\Common\Proxy\ProxyGenerator;

cli\line('Deleting proxy classes..');
if ($app->config->dataDir->dir('proxy')->exists()) {
    $app->config->dataDir->dir('proxy')->remove(true);
}

cli\line('Generating proxy classes..');
$app->em->getProxyFactory()->generateProxyClasses($app->em->getMetadataFactory()->getAllMetadata(), null);

// @hack http://www.doctrine-project.org/jira/browse/DCOM-282
cli\line('Modify proxy classes (DCOM-282)..');
foreach ($app->config->dataDir->dir('proxy') as $file) {
    $code = $file->open('r+')->read();
    $code = preg_replace('/return parent::([^\()]+)\([^\)]*\);/', "return call_user_func_array('parent::$1', func_get_args());", $code);
    $file->rewind()->truncate()->write($code)->close();
}