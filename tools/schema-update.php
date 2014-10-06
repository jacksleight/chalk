<?php
use Coast\Cli;

require __DIR__ . '/../../../../app.php';

$cli = new Cli();

$em		= $app->chalk->em;
$conn	= $em->getConnection();
$schema	= new \Doctrine\ORM\Tools\SchemaTool($em);

try {

	$em->beginTransaction();
	
	$cli->output('Updating schema..', true);
	$updateSchemaSql = $schema->getUpdateSchemaSql($em->getMetadataFactory()->getAllMetadata(), false);
	$key = array_search('DROP INDEX content ON core_index', $updateSchemaSql);
	if ($key !== false) {
		unset($updateSchemaSql[$key]);
	}
	if (count($updateSchemaSql)) {
		foreach ($updateSchemaSql as $sql) {
			$cli->output("  Executing '{$sql}'", true);
			$conn->exec($sql);
		}
		
		$cli->output('Comitting to database', true);
		$em->commit();
	} else {
		$cli->output('Nothing to update', true);
	}

	$cli->output('DONE', true);

} catch (Exception $e) {

	$em->rollback();
	$cli->error('ERROR: ' . $e->getMessage());
	throw $e;

}