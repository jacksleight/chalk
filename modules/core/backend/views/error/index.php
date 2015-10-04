<?php $this->outer('/layout/page', [
	'title'	=> 'Error',
]) ?>
<?php $this->block('main') ?>

<h1>Error</h1>
<?php if (isset($e)) { ?>
	<p class="error"><?= $e->getMessage() ?></p>
<?php } ?>