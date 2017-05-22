<?php if (in_array('update', $actions)) { ?>
    <a href="<?= $this->url([
        'action' => 'update',
        'id'     => $entity->id,
    ]) ?>">
<?php } ?>
    <?= $entity->previewName() ?><br>
    <small><?= implode(' – ', $entity->previewText(true)) ?></small>
<?php if (in_array('update', $actions)) { ?>
    </a>
<?php } ?>