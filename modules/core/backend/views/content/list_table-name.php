<?php if ($isEditAllowed) { ?>
    <a href="<?= $this->url([
        'action' => 'edit',
        'id'     => $entity->id,
    ]) ?>"><?php } ?><?= $entity->name ?><?php if ($isEditAllowed) { ?></a>
<?php } ?>
<br>
<small><?= implode(' – ', $entity->previewText($info->class != 'Chalk\Core\Content')) ?></small>