<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
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

if (!isset($_SERVER['argv'][1])) {
    cli\err("You must specify the module and tool name (module:tool)");
    exit;
}
$parts = explode(':', $_SERVER['argv'][1]);
if (count($parts) != 2) {
    cli\err("You must specify the module and tool name (module:tool)");
    exit;
}

$module = $app->module($parts[0]);
if (!isset($module)) {
    cli\err("Invalid module");
    exit;
}

$params = [];
$flags  = [];
$args   = [];
foreach (array_slice($_SERVER['argv'], 2) as $arg) {
    if (preg_match('/^--([\w-]+)=([\w-]+)$/', $arg, $match)) {
        $params[$match[1]] = $match[2];
    } else if (preg_match('/^--([\w-]+)$/', $arg, $match)) {
        $flags[] = $match[1];
    } else {
        $args[] = $arg;        
    }
}

$result = $module->execScript('tools', $parts[1], [$args, $flags, $params]);
if (!$result) {
    cli\err("Tool '{$module->name()}:{$parts[1]}' does not exist");
}