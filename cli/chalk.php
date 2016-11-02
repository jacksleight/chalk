<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

use Chalk\App as Chalk;

if (php_sapi_name() != 'cli') {
	exit("Must be run from the command line\n");
}

$init = getcwd() . '/chalk-cli.php';
if (!is_file($init)) {
	exit("No 'chalk-cli.php' initialization file found in '" . getcwd() . "'\n");
}
$app = require_once $init;
Chalk::isFrontend(false);

if (count($_SERVER['argv']) < 3) {
    cli\err("You must specify the module and command name");
    exit;
}

$module = $app->module($_SERVER['argv'][1]);
if (!isset($module)) {
    cli\err("Invalid module");
    exit;
}

$module->execScript('cli', $_SERVER['argv'][2]);