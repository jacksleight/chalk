<? $this->layout('/layouts/body') ?>
<? $this->block('main') ?>

<?= $content->main ?>

<? $this->block('sidebar') ?>

<nav class="menu menu-simple" role="navigation">
	<?php
	$items = [];
	foreach ($this->app->contentClasses() as $contentClass) {
		$contentType = \Ayre::type($contentClass);
		$items[] = [
			'label' => $contentType->plural,
			'name'	=> 'content',
			'params'=> ['action' => 'index', 'entityType' => $contentType->slug],
			// 'badge'	=> 120,
		];
	}
	?>
	<?= $this->render('nav', ['items' => $items]) ?>
</nav>