<?= $this->render('/element/form-input', array(
    'type'          => 'input_search',
    'entity'        => $model,
    'name'          => 'search',
    'autofocus'     => true,
    'placeholder'   => 'Search…',
), 'core') ?>