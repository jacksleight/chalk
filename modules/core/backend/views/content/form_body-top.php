<?= $this->render('/element/form-item', array(
	'entity'	=> $entity,
	'name'		=> 'name',
	'label'		=> 'Name',
	'autofocus'	=> true,
), 'core') ?>
<?php if (count($model->nodes)) { ?>
    <?= $this->render('/element/form-item', array(
        'entity'        => $entity->nodes[0],
        'name'          => 'name',
        'label'         => 'Label',
        'placeholder'   => $entity->name,
        'note'          => 'Alternative text used in navigation and URLs',
    ), 'core') ?>
<?php } ?>