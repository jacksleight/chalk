<?php
use Coast\Cli;

require __DIR__ . '/../../../../app.php';

$cli = new Cli();

$em		= $app->chalk->em;
$schema	= new \Doctrine\ORM\Tools\SchemaTool($em);

try {

	$em->beginTransaction();
	
	$cli->output('PREVIEW: Updating schema..', true);
	$updateSchemaSql = $schema->getUpdateSchemaSql($em->getMetadataFactory()->getAllMetadata(), false);
    $table = \Chalk\Chalk::info('Chalk\Core\Index')->name;
	$key = array_search("DROP INDEX content ON {$table}", $updateSchemaSql);
	if ($key !== false) {
		unset($updateSchemaSql[$key]);
	}
	if (count($updateSchemaSql)) {
		foreach ($updateSchemaSql as $sql) {
			$cli->output("  Executing '{$sql}'", true);
		}
		$cli->output('Comitting to database', true);
	} else {
		$cli->output('Nothing to update', true);
	}

	$cli->output('DONE', true);

} catch (Exception $e) {

	$em->rollback();
	$cli->error('ERROR: ' . $e->getMessage());
	throw $e;

}