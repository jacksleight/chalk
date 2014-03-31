<?php
use Coast\App\Request;

require __DIR__ . '/../app.php';


class CustomFileInfo extends \Gedmo\Uploadable\FileInfo\FileInfoArray
{
    public function isUploadedFile()
    {
        return false;
    }
}


$domain = new \Ayre\Domain();
$domain->name = 'Jack Sleight';


$uploadableListener->addEntityFileInfo($domain, new CustomFileInfo([
	'tmp_name'	=> '/Users/Jack/Desktop/test.png',
	'name'		=> 'test.png',
	'size'		=> 1000,
	'type'		=> 'image/png',
	'error'		=> 0	
]));

// $domain2 = new \Ayre\Domain();
// $domain2->name = 'Jack Sleight';
// $domain2->parent = $domain;

// $domain3 = new \Ayre\Domain();
// $domain3->name = 'Jack Sleight';
// $domain3->parent = $domain2;

// $domain4 = new \Ayre\Domain();
// $domain4->name = 'Jack Sleight';
// $domain4->parent = $domain2;


$em->persist($domain);
// $em->persist($domain2);
// $em->persist($domain3);
// $em->persist($domain4);
$em->flush();


die;



$app->execute(
	(new Request())->import()
)->export();