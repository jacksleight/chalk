<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once 'app.php';
return ConsoleRunner::createHelperSet($app->entity->em());