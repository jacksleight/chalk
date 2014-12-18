<?php
if (cli\choose("Are you sure", 'yn', 'n') == 'n') {
	exit;
}

cli\line('Clearing cache..');
$app->cache->deleteAll();

$schema	= new \Doctrine\ORM\Tools\SchemaTool($app->em);

cli\line('Calculating changes..');
$stmts = $schema->getUpdateSchemaSql($app->em->getMetadataFactory()->getAllMetadata(), false);
$table = \Chalk\Chalk::info('Chalk\Core\Index')->name;
$key = array_search("DROP INDEX content ON {$table}", $stmts);
if ($key !== false) {
	unset($stmts[$key]);
}
if (!count($stmts)) {
	cli\line("Nothing to update");
	exit;
}

cli\line("The following statements will be executed:\n  " . implode("\n  ", $stmts));
if (cli\choose("Execute these statements", 'yn', 'n') == 'n') {
	exit;
}

try {

	$app->em->beginTransaction();

	$bar = new cli\progress\Bar("Executing statements..", count($stmts), $interval = 1);
	$conn = $app->em->getConnection();
	foreach ($stmts as $stmt) {
		$conn->exec($stmt);
		$bar->tick();
	}
	$bar->finish();

	cli\line('Comitting to database..');
	$app->em->commit();

} catch (Exception $e) {

	$app->em->rollback();
	cli\err('Error: ' . $e->getMessage());

}