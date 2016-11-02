<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

return function() {

	$em = $this->app->em;

	if (cli\choose("Are you sure", 'yn', 'n') == 'n') {
		exit;
	}

	$this->execScript('tools', 'cache-clear');

	$schema	= new \Doctrine\ORM\Tools\SchemaTool($em->value());

	cli\line('Calculating changes..');
	$stmts = $schema->getUpdateSchemaSql($em->getMetadataFactory()->getAllMetadata(), false);
	if (!count($stmts)) {
		cli\line("Nothing to update");
		exit;
	}

	cli\line("The following statements will be executed:\n  " . implode("\n  ", $stmts));
	if (cli\choose("Execute these statements", 'yn', 'n') == 'n') {
		exit;
	}

	$em->beginTransaction();

	try {

		$bar = new cli\progress\Bar("Executing statements..", count($stmts), 1);
		$conn = $em->getConnection();
		foreach ($stmts as $stmt) {
			$conn->exec($stmt);
			$bar->tick();
		}
		$bar->finish();

		$em->commit();

	} catch (Exception $e) {

		$em->rollback();
		cli\err('Error: ' . $e->getMessage());

	}

	$this->execScript('tools', 'proxy-generate');

};