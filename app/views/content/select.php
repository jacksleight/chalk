<? if (!$req->isAjax()) { ?>
	<? $this->layout('/layouts/page_content') ?>
	<? $this->block('main') ?>
<? } ?>

<?= $this->render('/content/browser', [
	'method'	=> 'post',
	'close'		=> true,
	'thumbs'	=> $entity->name == 'core_file',
]) ?>