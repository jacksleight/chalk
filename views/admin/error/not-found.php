<?php
$req->addParams(array(
	'title'			=> 'Page Not Found',
), 'meta');
?>

<h1>Page Not Found</h1>
<p class="error"><?= $req->getParam('exception', new \Exception(), 'original')->getMessage() ?></p>