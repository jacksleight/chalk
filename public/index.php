<?php
use Coast\App\Request;

require __DIR__ . '/../app.php';
$app->execute(
	(new Request())->import()
)->export();