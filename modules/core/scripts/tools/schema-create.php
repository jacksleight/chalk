<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

use Chalk\InfoList;

return function() {

	$em = $this->app->em;

	if (cli\choose("Are you sure", 'yn', 'n') == 'n') {
		exit;
	}

	$this->execScript('tools', 'cache-clear');

	$schema	= new \Doctrine\ORM\Tools\SchemaTool($em->value());

	cli\line('Deleting existing files..');
	if ($this->app->config->dataDir->exists()) {
		$this->app->config->dataDir->remove(true);
	}
	if ($this->app->config->publicDataDir->exists()) {
		$this->app->config->publicDataDir->remove(true);
	}

	cli\line('Dropping existing database..');
	$schema->dropDatabase();

	$em->beginTransaction();

	try {

		cli\line('Creating schema..');
		$schema->createSchema($em->getMetadataFactory()->getAllMetadata());
	
		cli\line("Updating version numbers..");
		foreach ($this->app->modules() as $module) {
		    $setting = new Chalk\Core\Setting();
	        $setting->fromArray([
	            'name'  => "{$module->name()}_version",
	            "value" => $module->version(),
	        ]);
	        $em->persist($setting);
		}
		$em->flush();

		cli\line('Creating default user..');
		$user = new \Chalk\Core\User();
		$user->fromArray([
			'name'			=> 'Root',
			'emailAddress'	=> 'root@example.com',
			'passwordPlain'	=> 'password',
			'role'			=> \Chalk\Core\User::ROLE_DEVELOPER,
		]);
		$em->persist($user);
		$em->flush();
		
		cli\line('Creating default page..');
		$contentList = $this->backend->hook->fire('core_contentList', new InfoList('core_main'));
		$class = $contentList->first()->class;
		$page = new $class();
		$page->fromArray([
			'name'			=> 'Site',
		]);
		$em->persist($page);
		$em->flush();
		
		cli\line('Creating default structures..');
		$struct1 = new \Chalk\Core\Structure();
		$struct1->fromArray([
			'name'			=> 'Primary',
		]);
		$struct1->root->content = $page;
		$struct1->root->name    = 'Home';
		$em->persist($struct1);
		$em->flush();
		$struct2 = new \Chalk\Core\Structure();
		$struct2->fromArray([
			'name'			=> 'Secondary',
		]);
		$em->persist($struct2);
		$em->flush();

		cli\line('Creating default domain..');
		$domain = new \Chalk\Core\Domain();
		$domain->fromArray([
			'name'			=> 'example.com',
		]);
		$domain->structures->add($struct1);
		$domain->structures->add($struct2);
		$em->persist($domain);
		$em->flush();

		$em->commit();

	} catch (Exception $e) {

		$em->rollback();
		cli\err('Error: ' . $e->getMessage());

	}

	$this->execScript('tools', 'proxy-generate');

};