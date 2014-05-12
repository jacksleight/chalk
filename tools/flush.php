<?php
require __DIR__ . '/../../../../app.php';

header('Content-Type: text/plain');
$cli = new \Toast\Cli();

$em = $app->ayre->em;

try {

	$cli->status('Flushing Doctrine caches');
	$em->getConfiguration()->getQueryCacheImpl()->deleteAll();
	$em->getConfiguration()->getResultCacheImpl()->deleteAll();
	$em->getConfiguration()->getMetadataCacheImpl()->deleteAll();
	$cli->ok();

	$cli->message('DONE');

} catch (Exception $e) {

	$cli->error();
	throw $e;

}