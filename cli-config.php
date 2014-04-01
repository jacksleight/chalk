<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

$app = require_once 'app.php';
return ConsoleRunner::createHelperSet($app->em);