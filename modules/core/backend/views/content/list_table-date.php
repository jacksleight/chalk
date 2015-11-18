<?php if (isset($content->{"{$property}Date"})) { ?>
    <?= $content->{"{$property}Date"}->diffForHumans() ?>
<?php } else { ?>
    —
<?php } ?>