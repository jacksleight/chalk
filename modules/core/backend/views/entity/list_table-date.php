<?php
list($entity, $name) = Chalk\traverse_name($entity, $name);
?>
<?php if (isset($entity->{"{$name}Date"})) { ?>
    <?= $entity->{"{$name}Date"}->diffForHumans() ?>
<?php } else { ?>
    —
<?php } ?>