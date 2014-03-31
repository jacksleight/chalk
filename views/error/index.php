<? $this->layout('/layouts/page', [
	'title'	=> 'Error',
]) ?>
<? $this->block('main') ?>

<h1>Error</h1>
<? if (isset($e)) { ?>
	<p class="error"><?= $e->getMessage() ?></p>
<? } ?>