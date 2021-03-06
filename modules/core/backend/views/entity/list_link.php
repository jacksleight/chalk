<?php if (array_intersect(['update'], $actions)) { ?>
    <a href="<?= $this->url([
        'action'   => 'update',
        'id'       => $entity->id,
    ]) . $this->url->query([
        'tagsList' => $model->tagsList,
    ], true) ?>"><?= $content ?></a>
<?php } else if (array_intersect(['select-one'], $actions)) { ?>
    <?php
    $selectedUrl = isset($model->selectedUrl)
        ? $model->selectedUrl
        : $this->url([
            'action' => 'select',
        ]);
    ?>
    <a href="<?= $selectedUrl . $this->url->query([
        'mode'         => $model->mode,
        'selectedList' => $entity->id,
        'selectedType' => $info->name,
        'selectedSub'  => isset($sub) ? Chalk\Chalk::sub($sub, true) : null,
    ], true) ?>"><?= $content ?></a>
<?php } else { ?>
    <?= $content ?>
<?php } ?>