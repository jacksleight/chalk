<?php if ($entity->isNode()) { ?>
    <?= $this->render('/element/form-item', array(
        'entity'        => $entity->nodes[0],
        'name'          => 'isHidden',
        'label'         => 'Hidden',
    ), 'core') ?>
<?php } ?>