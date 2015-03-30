<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

if (cli\choose("Are you sure", 'yn', 'n') == 'n') {
	exit;
}

cli\line('Clearing cache..');
$app->cache->deleteAll();

$schema	= new \Doctrine\ORM\Tools\SchemaTool($app->em);

try {

	cli\line('Deleting existing files..');
	if ($app->root->dir('data')->exists()) {
		$app->root->dir('data')->remove(true);
	}
	if ($app->root->dir('public/data')->exists()) {
		$app->root->dir('public/data')->remove(true);
	}

	cli\line('Dropping existing database..');
	$schema->dropDatabase();

	$app->em->beginTransaction();
	
	cli\line('Creating schema..');
	$schema->createSchema($app->em->getMetadataFactory()->getAllMetadata());
    $table = \Chalk\Chalk::info('Chalk\Core\Index')->name;
	$app->em->getConnection()->exec("ALTER TABLE {$table} ADD FULLTEXT(content)");
	
	cli\line('Creating default user..');
	$user = new \Chalk\Core\User();
	$user->fromArray([
		'name'			=> 'Root',
		'emailAddress'	=> 'root@example.com',
		'passwordPlain'	=> 'password',
		'role'			=> \Chalk\Core\User::ROLE_DEVELOPER,
	]);
	$app->em->persist($user);
	$app->em->flush();
	
	cli\line('Creating default page..');
	$page = new \Chalk\Core\Page();
	$page->fromArray([
		'name'			=> 'Site',
	]);
	$app->em->persist($page);
	$app->em->flush();
	
	cli\line('Creating default structures..');
	$struct1 = new \Chalk\Core\Structure();
	$struct1->fromArray([
		'name'			=> 'Primary',
	]);
	$struct1->root->content = $page;
	$struct1->root->name    = 'Home';
	$app->em->persist($struct1);
	$app->em->flush();
	$struct2 = new \Chalk\Core\Structure();
	$struct2->fromArray([
		'name'			=> 'Secondary',
	]);
	$app->em->persist($struct2);
	$app->em->flush();

	cli\line('Creating default domain..');
	$domain = new \Chalk\Core\Domain();
	$domain->fromArray([
		'name'			=> 'example.com',
	]);
	$domain->structures->add($struct1);
	$domain->structures->add($struct2);
	$app->em->persist($domain);
	$app->em->flush();

	cli\line('Comitting to database..');
	$app->em->commit();

} catch (Exception $e) {

	$app->em->rollback();
	cli\err('Error: ' . $e->getMessage());

}