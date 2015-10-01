<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

if (cli\choose("Are you sure", 'yn', 'n') == 'n') {
	exit;
}

require_once $cmds['cache-clear']->name();

$schema	= new \Doctrine\ORM\Tools\SchemaTool($app->em->value());

cli\line('Calculating changes..');
$stmts = $schema->getUpdateSchemaSql($app->em->getMetadataFactory()->getAllMetadata(), false);
if (!count($stmts)) {
	cli\line("Nothing to update");
	exit;
}

cli\line("The following statements will be executed:\n  " . implode("\n  ", $stmts));
if (cli\choose("Execute these statements", 'yn', 'n') == 'n') {
	exit;
}

$app->em->beginTransaction();

try {

	$bar = new cli\progress\Bar("Executing statements..", count($stmts), 1);
	$conn = $app->em->getConnection();
	foreach ($stmts as $stmt) {
		$conn->exec($stmt);
		$bar->tick();
	}
	$bar->finish();

	$app->em->commit();

} catch (Exception $e) {

	$app->em->rollback();
	cli\err('Error: ' . $e->getMessage());

}

require_once $cmds['proxy-generate']->name();