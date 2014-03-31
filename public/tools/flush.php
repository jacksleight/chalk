<?php
$debug = null;

chdir(dirname(__FILE__));
$path = str_replace(DIRECTORY_SEPARATOR, '/', realpath('../../'));

require_once "{$path}/app.php";

header('Content-Type: text/plain');
$cli = new \Js\Cli();

try {

	if (!$app->isDevelopment()) {
		$cli->status('Flushing Doctrine APC cache');
		$apc->flushAll();
		$cli->ok();
	}

	$cli->message('DONE');

} catch (Exception $e) {

	$cli->error();
	throw $e;

}