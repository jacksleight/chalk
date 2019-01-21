<?php
list($entity, $name) = Chalk\traverse_name($entity, $name);
?>
<?php if (isset($entity->{"{$name}Date"})) { ?>
    <? if (isset($format)) { ?>
        <?= $entity->{"{$name}Date"}->format($format) ?>
    <? } else { ?>
        <?= $entity->{"{$name}Date"}->diffForHumans() ?>
    <? } ?>
<?php } else { ?>
    —
<?php } ?>
