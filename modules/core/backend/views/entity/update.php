<?php $this->outer('layout/page', [
    'title' => $entity->previewName,
], 'core') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post" data-modal-size="800">
    <?= $this->inner("form") ?>
    <?= $this->url->queryInputs() ?>
</form>