<?php
$required = !$md['nullable'];
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
	<?= false && isset($required) && $required ? "required" : null ?>
	<?= $entity->{$name} ? 'checked' : null ?>
	<?= isset($class) ? "class=\"{$class}\"" : null ?>>
<label for="<?= "_{$md['contextName']}" ?>">
	<?= $label ?>
</label>