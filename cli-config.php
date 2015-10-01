<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once 'app.php';
return ConsoleRunner::createHelperSet($app->entity->em);