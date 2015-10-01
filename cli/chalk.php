<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

use Chalk\Chalk;

if (php_sapi_name() != 'cli') {
	exit("Must be run from the command line\n");
}

$init = getcwd() . '/chalk-cli.php';
if (!is_file($init)) {
	exit("No 'chalk-cli.php' initialization file found in '" . getcwd() . "'\n");
}
$app = require_once $init;
Chalk::isFrontend(false);

$cmds = [];
foreach ($app->dir('cli/cmds') as $file) {
	$cmds[$file->fileName()] = $file;
}

if (!isset($_SERVER['argv'][1]) || !isset($cmds[$_SERVER['argv'][1]])) {
	cli\err("Invalid command, valid commands are:\n  " . implode("\n  ", array_keys($cmds)));
	exit;
}

require_once $cmds[$_SERVER['argv'][1]]->name();