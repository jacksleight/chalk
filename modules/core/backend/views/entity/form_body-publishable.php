<?php if ($info->is->publishable) { ?>
    <?= $this->render('/element/form-item', array(
        'entity'    => $entity,
        'name'      => 'publishDate',
        'label'     => 'Publish Date',
    ), 'core') ?>
    <?= $this->render('/element/form-item', array(
        'entity'    => $entity,
        'name'      => 'archiveDate',
        'label'     => 'Archive Date',
    ), 'core') ?>
<?php } ?>