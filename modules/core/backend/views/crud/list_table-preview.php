<?= $this->partial('link', [
    'entity'  => $entity,
    'content' => $this->inner('/element/preview-text', ['entity' => $entity], 'core'),
]) ?>