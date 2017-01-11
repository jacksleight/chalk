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

	$em->beginTransaction();

	try {

		try {
		    $settings = $em('core_setting')->all([]);
		} catch (Doctrine\DBAL\Exception\TableNotFoundException $e) {
		    $settings = [];
		}

		foreach ($this->app->modules() as $module) {

		    $to = $module->version();
		    if (isset($settings["{$module->name()}_version"])) {
		        $setting = $settings["{$module->name()}_version"];
		    } else {
		        $setting = new Chalk\Core\Setting();
		        $setting->fromArray([
		            'name'  => "{$module->name()}_version",
		            "value" => '0.0.0',
		        ]);
		        $em->persist($setting);
		    }
		    $from = $setting->value;
		    $setting->value = $to;
		    if (!isset($from) || !isset($to) || $from == $to) {
		    	continue;
		    }

		    $scripts = $module->scripts('migrations');
		    usort($scripts, 'version_compare');
		    $migrations = [];
		    foreach ($scripts as $script) {
		        if (version_compare($script, $from, '>') && version_compare($script, $to, '<=')) {
		            $migrations[] = $script;
		        }
		    }
		    if (!$migrations) {
				continue;
		    }

			cli\line("Migrating '{$module->name()}' module from '{$from}' to '{$to}'..");
		    foreach ($migrations as $migration) {
				cli\line("  Executing '{$migration}' script..");
		        $module->execScript('migrations', $migration);
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