<?php
$md	= isset($md)
	? $md
	: $entity->getMetadata(\Toast\Entity::MD_PROPERTY, $name);
?>
<?= $this->render("{$type}", ['md' => $md]) ?>