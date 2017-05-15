<?php if (!$req->isAjax()) { ?>
    <?php $this->outer('layout/page_site', [
        'title'   => $entity->isNew() ? "New {$info->singular}" : $entity->name,
    ], 'core') ?>
    <?php $this->block('main') ?>
<?php } ?>

<form action="<?= $this->url->route() . $this->url->query() ?>" method="post" data-modal-size="800">
    <?= $this->inner("form") ?>
</form>