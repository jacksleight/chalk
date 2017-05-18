<?php if ($isEditAllowed) { ?>
    <a href="<?= $this->url([
        'action' => 'update',
        'id'     => $entity->id,
    ]) ?>">
<?php } ?>
    <?= $entity->previewName() ?><br>
    <small><?= implode(' – ', $entity->previewText(true)) ?></small>
<?php if ($isEditAllowed) { ?>
    </a>
<?php } ?>