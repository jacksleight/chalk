<?php if (count($model->nodes)) { ?>
    <?= $this->render('/element/form-item', array(
        'entity'        => $entity->nodes[0],
        'name'          => 'isHidden',
        'label'         => 'Hidden',
    ), 'core') ?>
<?php } ?>