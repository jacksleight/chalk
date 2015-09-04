<?php $this->outer('/layout/page_content', [
	'content' => $content,
]) ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post">
    <?= $this->url->queryInputs() ?>
    <?= $this->inner("/{$info->local->path}/form", [], $info->module->name) ?>
</form>