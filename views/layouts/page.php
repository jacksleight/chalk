<? $this->layout('/layouts/body') ?>
<? $this->block('main') ?>

<?= $content->main ?>

<? $this->block('sidebar') ?>

<nav class="menu" role="navigation">
	<?= $this->render('nav', ['items' => [
		[
			'label' => 'Pages',
			'name'	=> 'content',
			'params'=> ['entityType' => 'core-page'],
		], [
			'label' => 'Files',
			'name'	=> 'content',
			'params'=> ['entityType' => 'core-file'],
		]
	]]) ?>
</nav>