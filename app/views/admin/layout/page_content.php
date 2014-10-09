<?php $this->layout('/layout/body') ?>
<?php $this->block('main') ?>

<?= $content->main ?>

<?php $this->block('sidebar') ?>

<nav class="menu menu-simple" role="navigation">
	<?php
	$items = [];
	$contents = $this->app->fire('Chalk\Core\Event\ListContents')->contents();
	foreach ($contents as $content) {
		$entity = \Chalk\Chalk::entity($content);
		$items[] = [
			'label' => $entity->plural,
			'name'	=> 'content',
			'params'=> ['action' => null, 'entity' => $entity->name],
			'badge' => $this->em($content)->fetchCountForPublish() ?: null,
		];
	}
	?>
	<?= $this->render('nav', ['items' => $items]) ?>
</nav>