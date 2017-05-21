<?= $this->parent() ?>
<?= $this->render('/element/form-item', array(
    'entity'    => $entity,
    'name'      => 'blocks',
    'label'     => 'Content',
    'type'      => 'array_textarea',
    'class'     => 'monospaced editor-content',
    'rows'      => 20,
    'stackable' => $req->user->isDeveloper(),
), 'core') ?>
<?= $this->render('/element/form-item', array(
    'entity'    => $entity,
    'name'      => 'image',
    'label'     => 'Image',
    'type'      => 'input_content',
), 'core') ?> 
<?= $this->render('/element/form-item', array(
    'type'      => 'textarea',
    'entity'    => $entity,
    'name'      => 'summary',
    'label'     => 'Summary',
    'class'     => 'monospaced editor-content',
    'rows'      => 5,
), 'core') ?>
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