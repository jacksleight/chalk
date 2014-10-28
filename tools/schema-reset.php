<?php
use Coast\Cli;

require __DIR__ . '/../../../../app.php';

$cli = new Cli();

$em		= $app->chalk->em;
$schema	= new \Doctrine\ORM\Tools\SchemaTool($em);

try {

	$em->beginTransaction();

	$cli->output('Dropping existing database', true);
	$schema->dropDatabase();
	
	$cli->output('Creating schema', true);
	$schema->createSchema($em->getMetadataFactory()->getAllMetadata());
    $table = \Chalk\Chalk::entity('Chalk\Core\Index')->name;
	$em->getConnection()->exec("ALTER TABLE {$table} ADD FULLTEXT(content)");
	
	$cli->output('Creating default user', true);
	$user = new \Chalk\Core\User();
	$user->fromArray([
		'name'			=> 'Root',
		'emailAddress'	=> 'root@example.com',
		'passwordPlain'	=> 'password',
		'role'			=> \Chalk\Core\User::ROLE_ROOT,
	]);
	$em->persist($user);
	$em->flush();
	
	$cli->output('Creating default page', true);
	$page = new \Chalk\Core\Page();
	$page->fromArray([
		'name'			=> 'Example',
	]);
	$em->persist($page);
	$em->flush();
	
	$cli->output('Creating default structures', true);
	$em->flush();
	$struct1 = new \Chalk\Core\Structure();
	$struct1->fromArray([
		'name'			=> 'Primary',
	]);
	$struct1->root->content = $page;
	$em->persist($struct1);
	$em->flush();
	$struct2 = new \Chalk\Core\Structure();
	$struct2->fromArray([
		'name'			=> 'Footer',
	]);
	$struct2->root->content = $page;
	$em->persist($struct2);
	$domain = new \Chalk\Core\Domain();
	$domain->fromArray([
		'name'			=> 'example.com',
		'structure'		=> $struct1,
	]);
	$em->persist($domain);
	$em->flush();

	$cli->output('Comitting to database', true);
	$em->commit();

	$cli->output('Deleting data', true);
	$app->dir('data')->remove(true);
	$app->dir('public/data')->remove(true);

	$cli->output('DONE', true);

} catch (Exception $e) {

	$em->rollback();
	$cli->error('ERROR: ' . $e->getMessage());
	throw $e;

}