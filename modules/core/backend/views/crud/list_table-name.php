<?php
$property = isset($property) ? $property : 'name';
?>
<?php if (in_array('update', $actions)) { ?>
    <a href="<?= $this->url([
        'action' => 'update',
        'id'     => $entity->id,
    ]) ?>">
<?php } ?>
    <?= $entity->{$property} ?>
<?php if (in_array('update', $actions)) { ?>
    </a>
<?php } ?>