<?= $this->render('/element/form-input', array(
    'type'          => 'dropdown_multiple',
    'entity'        => $model,
    'name'          => 'statuses',
    'icon'          => 'icon-status',
    'placeholder'   => 'Status',
), 'core') ?>