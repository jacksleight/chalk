<?php $this->outer('layout/page', [
    'title' => $entity->previewName,
], 'core') ?>
<?php $this->block('main') ?>

<form method="post" data-modal-size="800">
    <?= $this->inner("form") ?>
</form>