<? $this->layout('/layouts/body') ?>
<? $this->block('main') ?>

<?= $content->main ?>

<? $this->block('nav') ?>

<?= $this->render('nav', ['items' => [
	[
		'label' => 'Files',
		'name'	=> 'content',
		'params'=> ['entityType' => 'core-file'],
	], [
		'label' => 'Documents',
		'name'	=> 'content',
		'params'=> ['entityType' => 'core-document'],
	]
]]) ?>