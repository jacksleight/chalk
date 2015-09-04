<?php if (!$req->isAjax()) { ?>
    <?php $this->outer('/layout/page_content', [
        'content' => $content,
    ]) ?>
    <?php $this->block('main') ?>
<?php } ?>

<form action="<?= $this->url->route() . $this->url->query() ?>" method="post" data-modal-size="800px">
    <?= $this->inner("/{$info->local->path}/form", [], $info->module->name) ?>
</form>