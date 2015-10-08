<?= $this->render('/element/form-item', array(
    'entity'    => $content,
    'name'      => 'blocks',
    'label'     => 'Content',
    'type'      => 'array_textarea',
    'class'     => 'monospaced editor-content',
    'rows'      => 20,
    'stackable' => $req->user->isDeveloper(),
), 'core') ?>
<?= $this->render('/element/form-item', array(
    'type'      => 'textarea',
    'entity'    => $content,
    'name'      => 'summary',
    'label'     => 'Summary',
    'class'     => 'monospaced editor-content',
    'rows'      => 5,
), 'core') ?>
<?php if (!isset($content->data['delegate'])) { ?>
    <?= $this->render('/element/form-item', array(
        'type'      => 'select',
        'entity'    => $content,
        'name'      => 'layout',
        'label'     => 'Layout',
        'null'      => 'Default',
        'values'    => $this->app->layouts(),
    ), 'core') ?>
<?php } ?>