<?php $this->outer('/layout/html', [
	'htmlClass' => 'transparent',
]) ?>
<?php $this->block('body') ?>

<a href="<?= $this->url([], 'core_backend') ?>" class="btn btn-primary btn-icon icon-view" target="_blank"><span>View in Chalk</span></a>