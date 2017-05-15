<?= $this->render('/element/form-input', array(
    'type'          => 'dropdown_single',
    'entity'        => $index,
    'null'          => 'Any',
    'name'          => "{$property}DateMax",
    'icon'          => 'icon-calendar',
    'placeholder'   => $placeholder,
), 'core') ?>