<? $this->layout('/layouts/body') ?>
<? $this->block('main') ?>

<?= $content->main ?>

<? $this->block('nav') ?>

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