<?php
$md	= isset($md)
	? $md
	: $entity->getMetadata(\Toast\Entity::MD_PROPERTY, $name);
$disabled = isset($disabled)
	? $disabled
	: ($entity->getObject() instanceof \Chalk\Behaviour\Publishable && $entity->isArchived());
$render = isset($input)
	? $input
	: [$type, null];
?>
<?= $this->render($render[0], [
	'md' 		=> $md,
	'disabled' 	=> $disabled
], $render[1]) ?>