<?php if (isset($entity->{"{$property}Date"})) { ?>
    <?= $entity->{"{$property}Date"}->diffForHumans() ?>
<?php } else { ?>
    —
<?php } ?>
<?php if (isset($entity->{"{$property}User"})) { ?>
    <small>by <?= $entity->{"{$property}UserName"} ?></small>
<?php } ?>