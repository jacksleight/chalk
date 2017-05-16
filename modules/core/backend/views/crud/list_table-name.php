<?php
$property = isset($property) ? $property : 'name';
?>
<?php if ($isEditAllowed) { ?>
    <a href="<?= $this->url([
        'action' => 'update',
        'id'     => $entity->id,
    ]) ?>"><?php } ?><?= $entity->{$property} ?><?php if ($isEditAllowed) { ?></a>
<?php } ?>