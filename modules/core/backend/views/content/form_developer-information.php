<?= $this->render('/element/form-item', [
    'entity'        => $entity,
    'name'          => 'slug',
    'label'         => 'Slug',
    'type'          => 'input_pseudo',
    'readOnly'      => true,
], 'core') ?>
<?php if (isset($node)) { ?>
    <?= $this->render('/element/form-item', [
        'entity'        => $node,
        'name'          => 'id',
        'label'         => 'Node ID',
        'type'          => 'input_pseudo',
        'readOnly'      => true,
    ], 'core') ?>
    <?= $this->render('/element/form-item', [
        'entity'        => $node,
        'name'          => 'slug',
        'label'         => 'Node Slug',
        'type'          => 'input_pseudo',
        'readOnly'      => true,
    ], 'core') ?>
    <?= $this->render('/element/form-item', [
        'entity'        => $node,
        'name'          => 'path',
        'label'         => 'Node Path',
        'type'          => 'input_pseudo',
        'readOnly'      => true,
    ], 'core') ?>
<?php } ?>