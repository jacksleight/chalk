<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

use Chalk\Doctrine\Common\Proxy\ProxyGenerator;

return function() {

    $em = $this->app->em;

    cli\line('Deleting proxy classes..');
    if ($this->app->config->dataDir->dir('proxy')->exists()) {
        $this->app->config->dataDir->dir('proxy')->remove(true);
    }

    cli\line('Generating proxy classes..');
    $em->getProxyFactory()->generateProxyClasses($em->getMetadataFactory()->getAllMetadata(), null);

    // @hack http://www.doctrine-project.org/jira/browse/DCOM-282
    cli\line('Modifying proxy classes (DCOM-282)..');
    foreach ($this->app->config->dataDir->dir('proxy') as $file) {
        $code = $file->open('r+')->read();
        $code = preg_replace('/return parent::([^\()]+)\([^\)]*\);/', "return call_user_func_array('parent::$1', func_get_args());", $code);
        $file->rewind()->truncate()->write($code)->close();
    }

};