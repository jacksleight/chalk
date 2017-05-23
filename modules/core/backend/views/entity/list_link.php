<?php if (array_intersect(['update'], $actions)) { ?>
    <a href="<?= $this->url([
        'action'   => 'update',
        'id'       => $entity->id,
    ]) . $this->url->query([
        'tagsList' => $model->tagsList,
    ], true) ?>"><?= $content ?></a>
<?php } else if (array_intersect(['select-one'], $actions)) { ?>
    <a href="<?= $this->url([
        'action'   => 'select',
    ]) . $this->url->query([
        'mode'         => $model->mode,
        'selectedList' => $entity->id,
        'selectedType' => $model->selectedType,
    ], true) ?>"><?= $content ?></a>
<?php } else { ?>
    <?= $content ?>
<?php } ?>