<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
require_once __DIR__ . '/../app.php';
$entityManager = $em;
return ConsoleRunner::createHelperSet($entityManager);