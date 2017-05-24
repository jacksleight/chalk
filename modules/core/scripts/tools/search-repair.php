<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

use Chalk\Chalk;
use Chalk\Repository;

return function($args, $flags, $params) {

	$em = $this->app->em;

	if (!in_array('non-interactive', $flags)) {
		if (cli\choose("Are you sure", 'yn', 'n') == 'n') {
			exit;
		}
	}

	try {

		$conn = $em->getConnection();
		$table = Chalk::info('Chalk\Core\Search')->name;
		
		cli\line('Repairing index..');
		$conn->exec("REPAIR TABLE {$table} QUICK");

	} catch (Exception $e) {

		cli\err('Error: ' . $e->getMessage());

	}

};