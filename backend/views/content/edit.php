<?php $this->outer('/layout/page_content', [
	'content' => $content,
]) ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() . $this->url->query() ?>" method="post">
    <?= $this->inner("/{$info->local->path}/form", [], $info->module->name) ?>
</form>