<?php
use Coast\Cli;

require __DIR__ . '/../../../../app.php';

$cli = new Cli();

$em		= $app->chalk->em;
$schema	= new \Doctrine\ORM\Tools\SchemaTool($em);
$trans  = false;

try {

	$em->beginTransaction();
	$trans = true;

	$cli->output('Dropping existing database', true);
	$schema->dropDatabase();
	
	$cli->output('Creating schema', true);
	$schema->createSchema($em->getMetadataFactory()->getAllMetadata());
    $table = \Chalk\Chalk::info('Chalk\Core\Index')->name;
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
		'name'			=> 'Home',
	]);
	$em->persist($page);
	$em->flush();
	
	$cli->output('Creating default structures', true);
	$em->flush();
	$struct = new \Chalk\Core\Structure();
	$struct->fromArray([
		'name'			=> 'Site Hierarchy',
	]);
	$struct->root->content = $page;
	$em->persist($struct);
	$em->flush();

	$cli->output('Creating default domain', true);
	$domain = new \Chalk\Core\Domain();
	$domain->fromArray([
		'name'			=> 'example.com',
		'structure'		=> $struct,
	]);
	$em->persist($domain);
	$em->flush();

	include __DIR__ . '/schema-data.php';

	$cli->output('Comitting to database', true);
	$em->commit();
	$trans = false;

	$cli->output('Deleting data', true);
	if ($app->dir('data')->exists()) {
		$app->dir('data')->remove(true);
	}
	if ($app->dir('public/data')->exists()) {
		$app->dir('public/data')->remove(true);
	}

	$cli->output('DONE', true);

} catch (Exception $e) {

	if ($trans) {
		$em->rollback();
	}
	$cli->error('ERROR: ' . $e->getMessage());
	throw $e;

}