<?php if (!isset($entity->data['delegate'])) { ?>
    <?= $this->render('/element/form-item', array(
        'type'      => 'select',
        'entity'    => $entity,
        'name'      => 'layout',
        'label'     => 'Layout',
        'null'      => 'Default',
        'values'    => $this->app->layouts(),
    ), 'core') ?>
<?php } ?>
<?= $this->parent() ?>