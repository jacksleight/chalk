<?php if (isset($entity->{"{$property}Date"})) { ?>
    <?= $entity->{"{$property}Date"}->diffForHumans() ?>
<?php } else { ?>
    —
<?php } ?>