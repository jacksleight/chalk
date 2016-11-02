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

if (!isset($_SERVER['argv'][1])) {
    cli\err("You must specify the module and tool name");
    exit;
}
$parts = explode(':', $_SERVER['argv'][1]);
if (count($parts) != 2) {
    cli\err("You must specify the module and tool name");
    exit;
}

$module = $app->module($parts[0]);
if (!isset($module)) {
    cli\err("Invalid module");
    exit;
}

$result = $module->execScript('tools', $parts[1], array_slice($_SERVER['argv'], 2));
if (!$result) {
    cli\err("Tool '{$module->name()}:{$parts[1]}' does not exist");
}