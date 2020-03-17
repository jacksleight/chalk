<?php
list($entity, $name) = Chalk\traverse_name($entity, $name);
?>
<?php if (isset($entity->{"{$name}Date"})) { ?>
    <?php
    $value = $entity->{"{$name}Date"};
    $value->setTimezone(new \DateTimeZone($this->chalk->config->timezone));
    ?>
    <? if (isset($format)) { ?>
        <?= $value->format($format) ?>
    <? } else { ?>
        <?= $value->diffForHumans() ?>
    <? } ?>
<?php } else { ?>
    —
<?php } ?>
