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
			// 'badge'	=> 120,
		], [
			'label' => 'Articles',
			'name'	=> 'content',
			'params'=> ['entityType' => 'article-article'],
			// 'badge'	=> 12,
		], [
			'label' => 'People',
			'name'	=> 'content',
			'params'=> ['entityType' => 'app-person'],
			// 'badge'	=> 12,
		], [
			'label' => 'Files',
			'name'	=> 'content',
			'params'=> ['entityType' => 'core-file'],
			// 'badge'	=> 12,
		], 
		// [
		// 	'label' => 'URLs',
		// 	'name'	=> 'content',
		// 	'params'=> ['entityType' => 'core-url'],
		// 	// 'badge'	=> 12,
		// ], 
	]]) ?>
</nav>