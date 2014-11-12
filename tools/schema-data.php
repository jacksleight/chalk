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
		'eu',
		'amet nulla.',
		'risus. Quisque libero',
		'dictum cursus. Nunc mauris',
		'nunc risus varius orci,',
		'Vivamus nibh dolor, nonummy',
		'molestie orci tincidunt adipiscing.',
		'suscipit,',
		'ornare egestas',
		'consectetuer',
		'Nullam velit dui, semper',
		'accumsan interdum',
		'lacus.',
		'venenatis lacus. Etiam bibendum',
		'sed turpis nec',
		'tortor. Nunc commodo auctor',
		'sit amet,',
		'tempor diam dictum sapien.',
		'lobortis tellus justo sit',
		'eget tincidunt dui',
		'Duis volutpat nunc',
		'Duis',
		'eget tincidunt dui',
		'sem, vitae',
		'lobortis quis, pede. Suspendisse',
		'nulla. In tincidunt congue',
		'risus',
		'facilisis facilisis, magna tellus',
		'convallis',
		'accumsan',
		'consequat, lectus',
		'vitae, posuere at, velit.',
		'consectetuer',
		'non',
		'sociosqu ad',
		'nibh. Phasellus nulla.',
		'fringilla euismod',
		'nulla. Cras eu tellus',
		'Curabitur massa.',
		'et arcu',
		'placerat, orci',
		'amet luctus vulputate, nisi',
		'urna',
		'gravida. Aliquam',
		'Morbi accumsan laoreet ipsum.',
		'Fusce aliquam,',
		'nec tellus. Nunc lectus',
		'lectus pede et risus.',
		'elit, pellentesque',
		'ac mattis semper,',
		'vestibulum nec, euismod in,',
		'euismod enim.',
		'ornare, lectus',
		'mollis lectus pede et',
		'magna. Phasellus dolor',
		'elit fermentum risus,',
		'dapibus ligula.',
		'Suspendisse',
		'blandit congue. In scelerisque',
		'et',
		'a, scelerisque',
		'id',
		'Vivamus non lorem vitae',
		'ornare. Fusce mollis.',
		'velit dui, semper et,',
		'ut',
		'leo elementum sem, vitae',
		'Praesent eu dui. Cum',
		'non, lobortis',
		'elit, pretium et,',
		'dui. Suspendisse ac',
		'Suspendisse',
		'arcu. Sed eu nibh',
		'tempus scelerisque, lorem ipsum',
		'sagittis felis. Donec tempor,',
		'Etiam gravida',
		'in felis. Nulla',
		'sagittis semper.',
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