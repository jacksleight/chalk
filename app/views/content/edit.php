<?php $this->parent('/layout/page_content', [
	'content' => $content,
]) ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post">
	<?= $this->child("/{$info->local->path}/form", [], $info->module->class) ?>
</form>