<?php
list($entity, $name) = Chalk\traverse_name($entity, $name);
$true  = isset($true)  ? $true  : '';
$false = isset($false) ? $false : '';
?>
<i class="icon-<?= $entity->{$name} ? $true : $false ?>"></i>