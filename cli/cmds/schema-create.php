<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

if (cli\choose("Are you sure", 'yn', 'n') == 'n') {
	exit;
}

require_once $cmds['cache-clear']->name();

$schema	= new \Doctrine\ORM\Tools\SchemaTool($app->em->value());

cli\line('Deleting existing files..');
if ($app->config->dataDir->exists()) {
	$app->config->dataDir->remove(true);
}
if ($app->config->publicDataDir->exists()) {
	$app->config->publicDataDir->remove(true);
}

cli\line('Dropping existing database..');
$schema->dropDatabase();

$app->em->beginTransaction();

try {

	cli\line('Creating schema..');
	$schema->createSchema($app->em->getMetadataFactory()->getAllMetadata());
	
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

	$app->em->commit();

} catch (Exception $e) {

	$app->em->rollback();
	cli\err('Error: ' . $e->getMessage());

}

require_once $cmds['proxy-generate']->name();