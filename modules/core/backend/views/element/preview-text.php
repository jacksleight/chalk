<?php
$context = isset($context) ? $context : true;
?>
<?php if (isset($icon) && $icon) { ?>
    <i class="icon-<?= $entity->typeIcon ?>"></i>
<?php } ?>
<?= $entity->previewName() ?><br>
<small><?= $this->strip(implode(' – ', $entity->previewText($context))) ?></small>