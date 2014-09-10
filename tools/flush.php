<?php
use Coast\Cli;

require __DIR__ . '/../../../../app.php';

header('Content-Type: text/plain');
$cli = new Cli();

$em = $app->ayre->em;

try {

	$cli->output('Flushing Doctrine caches', true);
	$em->getConfiguration()->getQueryCacheImpl()->deleteAll();
	$em->getConfiguration()->getResultCacheImpl()->deleteAll();
	$em->getConfiguration()->getMetadataCacheImpl()->deleteAll();

	$cli->output('DONE', true);

} catch (Exception $e) {

	$cli->error('ERROR: ' . $e->getMessage());
	throw $e;

}