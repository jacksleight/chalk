<?php if ($info->is->tagable) { ?>
    <?= $this->render('/element/form-item', array(
        'type'      => 'input_tag',
        'entity'    => $entity,
        'name'      => 'tagNamesList',
        'label'     => 'Tags',
        'values'    => $this->em('core_tag')->all(),
    ), 'core') ?>
<?php } ?>