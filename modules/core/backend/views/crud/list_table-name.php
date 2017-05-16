<?php
$property = isset($property) ? $property : 'name';
?>
<?php if ($isEditAllowed) { ?>
    <a href="<?= $this->url([
        'action' => 'edit',
        'id'     => $entity->id,
    ]) ?>"><?php } ?><?= $entity->{$property} ?><?php if ($isEditAllowed) { ?></a>
<?php } ?>