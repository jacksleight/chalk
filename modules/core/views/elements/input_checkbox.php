<?php
$required = $md['validator']->hasValidator('Toast\Validator\Set');
?>
<input
	type="hidden"
	name="<?= "{$md['contextName']}" ?>"
	value="0">
<input
	type="checkbox"
	name="<?= "{$md['contextName']}" ?>"
	id="<?= "_{$md['contextName']}" ?>"
	value="1"
	<?= isset($disabled) && $disabled ? "disabled" : null ?>
	<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
	<?= isset($required) && $required ? "required" : null ?>
	<?= $entity->{$name} ? 'checked' : null ?>
	<?= isset($class) ? "class=\"{$class}\"" : null ?>>
<label for="<?= "_{$md['contextName']}" ?>">
	<?= $label ?>
</label>