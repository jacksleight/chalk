<?php if (!$req->isAjax()) { ?>
	<?php $this->layout('/layout/page_content') ?>
	<?php $this->block('main') ?>
<?php } ?>

<?= $this->render('/content/browser', [
	'method'	=> 'post',
	'close'		=> true,
	'thumbs'	=> $entity->class == 'Chalk\Core\File',
]) ?>