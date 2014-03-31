<?php
$md	= isset($md)
	? $md
	: $entity->getMetadata(\Js\Entity::MD_PROPERTY, $name);
?>
<?= $this->render("{$type}", ['md' => $md]) ?>