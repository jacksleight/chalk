<?php if (true) { ?>
    <?= $this->render('/element/form-item', array(
        'type'      => 'input_tag',
        'entity'    => $entity,
        'name'      => 'tagsText',
        'label'     => 'Tags',
        'values'    => $this->em('core_tag')->all(),
        'disabled'  => $entity->isProtected(),
    ), 'core') ?>
<?php } ?>
<?php if (is_a($info->class, 'Chalk\Core\Behaviour\Publishable', true)) { ?>
    <?= $this->render('/element/form-item', array(
        'entity'    => $entity,
        'name'      => 'publishDate',
        'label'     => 'Publish Date',
    ), 'core') ?>
    <?= $this->render('/element/form-item', array(
        'entity'    => $entity,
        'name'      => 'archiveDate',
        'label'     => 'Archive Date',
    ), 'core') ?>
<?php } ?>