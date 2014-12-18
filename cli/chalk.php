<?php
if (php_sapi_name() != 'cli') {
	exit("Must be run from the command line\n");
}

$init = getcwd() . '/chalk-cli.php';
if (!is_file($init)) {
	exit("No 'chalk-cli.php' initialization file found in '" . getcwd() . "'\n");
}
$app = require_once $init;

$cmds = [];
foreach ($app->dir('cli/cmds') as $file) {
	$cmds[$file->fileName()] = $file;
}

if (!isset($_SERVER['argv'][1]) || !isset($cmds[$_SERVER['argv'][1]])) {
	cli\err("Invalid command, valid commands are:\n  " . implode("\n  ", array_keys($cmds)));
	exit;
}

require_once $cmds[$_SERVER['argv'][1]]->name();