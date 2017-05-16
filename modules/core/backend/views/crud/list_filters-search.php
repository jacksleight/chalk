<?= $this->render('/element/form-input', array(
    'type'          => 'input_search',
    'entity'        => $index,
    'name'          => 'search',
    'autofocus'     => true,
    'placeholder'   => 'Search…',
), 'core') ?>