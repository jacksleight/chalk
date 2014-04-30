<?php
require __DIR__ . '/../../app.php';

$em = $app->ayre->em;

$file = new \Ayre\Entity\File();
$file->newFile = $app->file('ayre/assets/images/logo.svg');
$em->persist($file);
$em->flush();
