<?php
list($entity, $name) = Chalk\traverse_name($entity, $name);
?>
<span class="badge badge-upper badge-<?= $this->app->statusClass($entity->{$name}) ?>">
    <?= $entity->{$name} ?>
</span>