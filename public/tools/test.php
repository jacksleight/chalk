<?php
require __DIR__ . '/../../app.php';

global $blameable;
$blameable->setUserValue($app->entity('User')->find(1));

$file = $app->entity('File')->find(13);
$app->entity->remove($file);
$app->entity->flush();


$file = new \Ayre\Entity\File();
$file->file = new \Coast\File('/Server/example/example.jpg');
$app->entity->persist($file);
$app->entity->flush();
