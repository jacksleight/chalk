<?= $this->render('/element/form-item', [
    'entity'        => $entity,
    'name'          => 'slug',
    'label'         => 'Slug',
    'type'          => 'input_pseudo',
    'readOnly'      => true,
], 'core') ?>
<?php if ($entity->isNode()) { ?>
    <?= $this->render('/element/form-item', [
        'entity'        => $entity->nodes[0],
        'name'          => 'slug',
        'label'         => 'Node Slug',
        'type'          => 'input_pseudo',
        'readOnly'      => true,
    ], 'core') ?>
    <?= $this->render('/element/form-item', [
        'entity'        => $entity->nodes[0],
        'name'          => 'path',
        'label'         => 'Node Path',
        'type'          => 'input_pseudo',
        'readOnly'      => true,
    ], 'core') ?>
<?php } ?>