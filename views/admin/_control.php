<?php
$md	= isset($md)
	? $md
	: $entity->getMetadata(\Js\Entity::MD_PROPERTY, $name);
if ($type != 'input_checkbox' && $type != 'input_checkboxes') {
	$class = isset($class)
		? $class . ' form-control'
		: 'form-control';
}
?>
<?= $this->render("/_element/{$type}", [
	'md'	=> $md,
	'class'	=> isset($class) ? $class : null,
], 'app') ?>