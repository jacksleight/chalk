<?php
list($name, $set) = isset($input)
	? $input
	: [$type, null];
$md	= isset($md)
	? $md
	: $entity->getMetadata(\Toast\Entity::MD_PROPERTY, $name);
$disabled = isset($disabled)
	? $disabled
	: ($entity->getObject() instanceof \Ayre\Behaviour\Publishable && $entity->isArchived());
?>
<?= $this->render($name, [
	'md' 		=> $md,
	'disabled' 	=> $disabled
], $set) ?>