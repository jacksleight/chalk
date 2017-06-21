<?php
list($entity, $name) = Chalk\traverse_name($entity, $name);
?>
<?php if (isset($entity->{"{$name}Date"})) { ?>
    <?= $entity->{"{$name}Date"}->diffForHumans() ?>
<?php } else { ?>
    —
<?php } ?>
<?php if (isset($entity->{"{$name}User"})) { ?>
    <small>by <?= $entity->{"{$name}UserName"} ?></small>
<?php } ?>