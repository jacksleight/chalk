<?php if ($isEditAllowed) { ?>
    <a href="<?= $this->url([
        'action' => 'edit',
        'id'     => $entity->id,
    ]) ?>"><?php } ?><?= $entity->name ?><?php if ($isEditAllowed) { ?></a>
<?php } ?>