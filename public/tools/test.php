<?php
require __DIR__ . '/../../app.php';

global $blameable;
$blameable->setUserValue($app->entity('User')->find(1));

$silts = $app->entity('Document')->findAll();
var_dump(count($silts));