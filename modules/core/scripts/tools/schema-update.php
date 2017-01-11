<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

return function($args, $flags, $params) {

	$em = $this->app->em;

	if (!in_array('non-interactive', $flags)) {
		if (cli\choose("Are you sure", 'yn', 'n') == 'n') {
			exit;
		}
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
	if (!in_array('non-interactive', $flags)) {
		if (cli\choose("Execute these statements", 'yn', 'n') == 'n') {
			exit;
		}
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

		cli\line("Updating version numbers..");
		$settings = $em('core_setting')->all([]);
		foreach ($this->app->modules() as $module) {
		    if (isset($settings["{$module->name()}_version"])) {
		        $setting = $settings["{$module->name()}_version"];
		    } else {
		        $setting = new Chalk\Core\Setting();
		        $setting->fromArray([
		            'name'  => "{$module->name()}_version",
		            "value" => $module->version(),
		        ]);
		        $em->persist($setting);
		    }
		}
		$em->flush();

		$em->commit();

	} catch (Exception $e) {

		$em->rollback();
		cli\err('Error: ' . $e->getMessage());

	}

	$this->execScript('tools', 'proxy-generate');

};