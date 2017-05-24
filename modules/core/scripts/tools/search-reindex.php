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

	cli\line('Clearing cache..');
	$this->app->cache->deleteAll();

	try {

		$conn = $em->getConnection();
		$table = Chalk::info('Chalk\Core\Search')->name;
		
		cli\line('Clearing index..');
		$conn->exec("TRUNCATE TABLE {$table}");

		$metas = $em->getMetadataFactory()->getAllMetadata();
		foreach ($metas as $meta) {
			if (!is_a($meta->name, 'Chalk\Core\Behaviour\Searchable', true) || $meta->name != $meta->rootEntityName) {
				continue;
			}
			$limit	= 100;
			$page	= 0;
			do {
				$page++;	
				$entities = $em($meta->name)->all([
					'limit' => $limit,
					'page'  => $page,
				], [], Repository::FETCH_ALL_PAGED);
				if ($page == 1) {
					$total = $entities->count();
					$pages = ceil($total / $limit);
					$bar   = new cli\progress\Bar("Indexing {$meta->name}..", $total, 1);
					if ($total == 0) {
						break;
					}
				}
				$indexes = $em('Chalk\Core\Search')->entities($entities);
				foreach ($indexes as $index) {
					$index->reindex();
					$bar->tick();
				}
				$em->flush();	
				$em->clear();
			} while ($page < $pages);
			$bar->finish();
		}

	} catch (Exception $e) {

		cli\err('Error: ' . $e->getMessage());

	}

};