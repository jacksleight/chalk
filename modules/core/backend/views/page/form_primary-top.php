<?= $this->parent() ?>
<?= $this->render('/element/form-item', array(
    'entity'    => $entity,
    'name'      => 'blocks',
    'label'     => 'Content',
    'type'      => 'array_textarea',
    'class'     => 'monospaced editor-content',
    'rows'      => 20,
    'stackable' => $this->user->isDeveloper(),
), 'core') ?>
<?= $this->render('/element/form-item', array(
    'type'      => 'textarea',
    'entity'    => $entity,
    'name'      => 'summary',
    'label'     => 'Summary',
    'class'     => 'monospaced editor-content',
    'rows'      => 5,
), 'core') ?>
<?= $this->render('/element/form-item', array(
    'entity'    => $entity,
    'name'      => 'image',
    'label'     => 'Image',
    'type'      => 'input_chalk',
    'filters'   => 'core_info_image',
), 'core') ?>