<?php
require __DIR__ . '/../../app.php';

$em = $app->chalk->em;

$file = new \Chalk\Core\File();
$file->newFile = $app->file('chalk/assets/images/logo.svg');
$em->persist($file);
$em->flush();
