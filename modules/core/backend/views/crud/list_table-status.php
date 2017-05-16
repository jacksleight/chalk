<?php
$property = isset($property) ? $property : 'status';
?>
<span class="badge badge-upper badge-<?= $this->app->statusClass($entity->{$property}) ?>">
    <?= $entity->{$property} ?>
</span>