<?php $this->layout('/layout/body') ?>
<?php $this->block('main') ?>

<?= $content->main ?>

<?php $this->block('sidebar') ?>

<nav class="menu menu-simple" role="navigation">
	<?php
	$items = [];
	$contents = $this->app->fire('Chalk\Core\Event\ListContents')->contents();
	foreach ($contents as $content) {
		$info = \Chalk\Chalk::info($content);
		$items[] = [
			'label' => $info->plural,
			'name'	=> 'content',
			'params'=> ['action' => null, 'entity' => $info->name],
			'badge' => $this->em($content)->count(['isPublishable' => true]) ?: null,
		];
	}
	?>
	<?= $this->render('nav', ['items' => $items]) ?>
</nav>