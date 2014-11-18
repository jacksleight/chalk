<?php
// use Coast\Cli;

// require __DIR__ . '/../../../../app.php';

// $cli = new Cli();

// $em		= $app->chalk->em;
// $schema	= new \Doctrine\ORM\Tools\SchemaTool($em);

// try {

// 	$em->beginTransaction();

	$cli->output('Creating test data', true);

	$html = '
		<h1>{0}</h1>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illo dignissimos explicabo eius autem dolorum, ducimus, possimus sed sint! Perspiciatis praesentium at eum ea molestiae. Aut voluptas alias magni, amet sit.</p>
		<p>Cumque, earum culpa maxime ex facere eaque, natus mollitia rem quia nam in. Voluptas reiciendis eum a porro ut provident, totam veniam, itaque autem rem deserunt vel in, molestiae nam!</p>
		<p>Unde doloribus vero quos labore, qui consequatur perspiciatis sit accusantium aut explicabo doloremque error ducimus ipsa nemo exercitationem, omnis dolores earum non tenetur a! Dignissimos delectus cumque nemo voluptate nihil!</p>
		<p>Iusto a cum esse provident maiores incidunt doloribus voluptate, animi quibusdam molestias. Eveniet ad nemo eius excepturi quisquam repellat officiis distinctio, adipisci cum saepe porro dicta, iure deserunt mollitia nostrum!</p>
	';
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
	shuffle($names);
	$nodes = [
		$struct->root,
	];
	foreach ($names as $name) {
		$name = ucwords($name);
		$page = new \Chalk\Core\Page();
		$page->fromArray([
			'name'		=> $name,
			'blocks'	=> [['name' => 'primary', 'value' => str_replace('{0}', $name, $html)]],
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