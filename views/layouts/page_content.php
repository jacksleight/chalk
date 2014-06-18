<? $this->layout('/layouts/body') ?>
<? $this->block('main') ?>

<?= $content->main ?>

<? $this->block('sidebar') ?>

<nav class="menu menu-simple" role="navigation">
	<?php
	$items = [];
	foreach ($this->app->contentClasses() as $contentClass) {
		$entity = \Ayre::entity($contentClass);
		$items[] = [
			'label' => $entity->plural,
			'name'	=> 'content',
			'params'=> ['action' => 'index', 'entity' => $entity->name],
		];
	}
	?>
	<?= $this->render('nav', ['items' => $items]) ?>
</nav>