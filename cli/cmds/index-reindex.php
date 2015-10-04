<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

use Chalk\App as Chalk;

if (cli\choose("Are you sure", 'yn', 'n') == 'n') {
	exit;
}

cli\line('Clearing cache..');
$app->cache->deleteAll();

try {

	cli\line('Clearing index..');
	$conn = $app->em->getConnection();
	$table = Chalk::info('Chalk\Core\Index')->name;
	$conn->exec("TRUNCATE TABLE {$table}");

	$metas = $app->em->getMetadataFactory()->getAllMetadata();
	foreach ($metas as $meta) {
		if (!is_a($meta->name, 'Chalk\Core\Behaviour\Searchable', true) || $meta->name != $meta->rootEntityName) {
			continue;
		}
		$limit	= 100;
		$page	= 0;
		do {
			$page++;	
			$entities = $app->em($meta->name)->paged([], null, $limit, $page);
			if ($page == 1) {
				$total = $entities->count();
				$pages = ceil($total / $limit);
				$bar   = new cli\progress\Bar("Indexing {$meta->name}..", $total, 1);
			}
			$indexes = $app->em('Chalk\Core\Index')->entities($entities);
			foreach ($indexes as $index) {
				$index->reindex();
				$bar->tick();
			}
			$app->em->flush();	
			$app->em->clear();
		} while ($page < $pages);
		$bar->finish();
	}

} catch (Exception $e) {

	cli\err('Error: ' . $e->getMessage());

}