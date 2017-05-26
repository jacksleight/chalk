<?php $this->outer('/layout/html', [
	'htmlClass' => 'transparent',
]) ?>
<?php $this->block('body') ?>

<a href="<?= $this->url([], 'core_backend') ?>" class="btn btn-primary btn-icon icon-pencil" target="_blank"><span>Edit in Chalk</span></a>