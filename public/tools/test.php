<?php
require __DIR__ . '/../../app.php';

$file = new \Ayre\Entity\File();

$md = $file->getMetadata();

var_dump($md);