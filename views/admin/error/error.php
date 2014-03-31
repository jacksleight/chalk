<?php
$req->addParams(array(
	'title'			=> 'Error',
), 'meta');
?>

<h1>Error</h1>
<p class="error"><?= $req->getParam('exception', new \Exception(), 'original')->getMessage() ?></p>