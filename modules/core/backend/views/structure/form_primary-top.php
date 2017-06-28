<?= $this->render('/element/form-item', array(
    'entity'    => $entity,
    'name'      => 'name',
    'label'     => 'Name',
    'autofocus' => true,
), 'core') ?>
<?= $this->render('/element/form-item', array(
    'entity'    => $entity,
    'name'      => 'path',
    'label'     => 'URL Path Prefix',
)) ?>