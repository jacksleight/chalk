<?php
$md	= isset($md)
	? $md
	: $entity->getMetadata(\Ayre\Entity::MD_PROPERTY, $name);
?>
<?= $this->render("{$type}", ['md' => $md]) ?>