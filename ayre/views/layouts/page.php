<? $this->layout('/layouts/body') ?>
<? $this->block('main') ?>

<?= $content->main ?>

<? $this->block('sidebar') ?>

<nav class="menu menu-simple" role="navigation">
	<?= $this->render('nav', ['items' => [
		[
			'label' => 'Pages',
			'name'	=> 'content',
			'params'=> ['entityType' => 'core-page'],
		], [
			'label' => 'Files',
			'name'	=> 'content',
			'params'=> ['entityType' => 'core-file'],
		], 
		// [
		// 	'label' => 'URLs',
		// 	'name'	=> 'content',
		// 	'params'=> ['entityType' => 'core-url'],
		// ]
	]]) ?>
</nav>