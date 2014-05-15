<?php
require __DIR__ . '/../../../../app.php';

header('Content-Type: text/plain');
$cli = new \Toast\CLI();

$em		= $app->ayre->em;
$conn	= $em->getConnection();
$schema	= new \Doctrine\ORM\Tools\SchemaTool($em);

try {

	$em->beginTransaction();
	
	$cli->message('Updating schema..');
	$updateSchemaSql = $schema->getUpdateSchemaSql($em->getMetadataFactory()->getAllMetadata(), false);
	$key = array_search('DROP INDEX content ON core_index', $updateSchemaSql);
	if ($key !== false) {
		unset($updateSchemaSql[$key]);
	}
	if (count($updateSchemaSql)) {
		foreach ($updateSchemaSql as $sql) {
			$cli->status("Executing '{$sql}'", 1);
			$conn->exec($sql);
			$cli->ok();
		}
		
		$cli->status('Comitting to database');
		$em->commit();
		$cli->ok();
	} else {
		$cli->message('Nothing to update.', 1);
	}

	$cli->message('DONE');

} catch (Exception $e) {

	$em->rollback();
	$cli->error();
	throw $e;

}