<?= $this->render('/element/form-item', [
    'entity'        => $entity,
    'name'          => 'slug',
    'label'         => 'Slug',
    'type'          => 'input_pseudo',
    'readOnly'      => true,
], 'core') ?>