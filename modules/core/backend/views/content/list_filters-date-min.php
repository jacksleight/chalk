<?= $this->render('/element/form-input', array(
    'type'          => 'dropdown_single',
    'entity'        => $index,
    'null'          => 'Any',
    'name'          => "{$property}DateMin",
    'icon'          => 'icon-calendar',
    'placeholder'   => $placeholder,
), 'core') ?>