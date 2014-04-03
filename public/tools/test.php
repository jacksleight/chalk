<?php
require __DIR__ . '/../../app.php';

global $blameable;
$blameable->setUserValue($app->entity('User')->find(1));

$contents = $app->entity('Document')->findAll();
var_dump(count($contents));