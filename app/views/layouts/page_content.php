<? $this->layout('/layouts/body') ?>
<? $this->block('main') ?>

<?= $content->main ?>

<? $this->block('sidebar') ?>

<nav class="menu menu-simple" role="navigation">
	<?php
	$items = [];
	foreach ($this->app->contentClasses() as $contentClass) {
		$entity = \Chalk::entity($contentClass);
		$items[] = [
			'label' => $entity->plural,
			'name'	=> 'content',
			'params'=> ['action' => null, 'entity' => $entity->name],
			'badge' => $this->em($contentClass)->fetchCountForPublish() ?: null,
		];
	}
	?>
	<?= $this->render('nav', ['items' => $items]) ?>
</nav>