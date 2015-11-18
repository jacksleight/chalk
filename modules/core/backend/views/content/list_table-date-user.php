<?php if (isset($content->{"{$property}Date"})) { ?>
    <?= $content->{"{$property}Date"}->diffForHumans() ?>
<?php } else { ?>
    —
<?php } ?>
<?php if (isset($content->{"{$property}User"})) { ?>
    <small>by <?= $content->{"{$property}UserName"} ?></small>
<?php } ?>