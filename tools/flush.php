<?php
use Coast\Cli;

require __DIR__ . '/../../../../app.php';

header('Content-Type: text/plain');
$cli = new Cli();

try {

	$cli->output('Flushing cache', true);
	$app->chalk->cache->deleteAll();

	$cli->output('DONE', true);

} catch (Exception $e) {

	$cli->error('ERROR: ' . $e->getMessage());
	throw $e;

}