<?php
// use Coast\Cli;

// require __DIR__ . '/../../../../app.php';

// $cli = new Cli();

// $em		= $app->chalk->em;
// $schema	= new \Doctrine\ORM\Tools\SchemaTool($em);

// try {

// 	$em->beginTransaction();

	$cli->output('Creating test data', true);

	$names = array(
		'name',
		'lobortis augue scelerisque mollis.',
		'aliquet',
		'adipiscing elit.',
		'dictum ultricies',
		'vel, mauris.',
		'accumsan laoreet',
		'enim nisl elementum',
		'tellus faucibus',
		'Donec',
		'eleifend. Cras sed',
		'ac tellus. Suspendisse',
		'egestas. Sed pharetra,',
		'magnis',
		'mi pede, nonummy',
		'per',
		'vulputate eu, odio. Phasellus',
		'nec ante.',
		'Donec dignissim',
		'Vivamus euismod urna. Nullam',
		'nisl. Quisque fringilla',
		'nec,',
		'ante. Vivamus non lorem',
	);
	$nodes = [
		$struct->root,
	];
	foreach ($names as $name) {
		$page = new \Chalk\Core\Page();
		$page->fromArray([
			'name' => $name,
		]);
		$em->persist($page);
		$node = new \Chalk\Core\Structure\Node();
		$node->content = $page;
		$node->parent = rand(0, 10) ? $nodes[array_rand($nodes)] : $nodes[0];
		$em->persist($node);
		$nodes[] = $node;
	}
	$em->flush();

// 	$cli->output('Comitting to database', true);
// 	$em->commit();

// 	$cli->output('DONE', true);

// } catch (Exception $e) {

// 	$em->rollback();
// 	$cli->error('ERROR: ' . $e->getMessage());
// 	throw $e;

// }