<? if (!$req->isAjax()) { ?>
	<? $this->layout('/layouts/page_content') ?>
	<? $this->block('main') ?>
<? } ?>

<?= $this->render('/content/browser', [
	'close'			=> true,
	'thumbs'		=> $entityType->name == 'core_file',
]) ?>