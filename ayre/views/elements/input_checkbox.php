<?php
$required = $md['validator']->hasValidator('Toast\Validator\Set');
?>
<input
	type="hidden"
	name="<?= implode('_', $md['context']) ?>"
	value="0">
<input
	type="checkbox"
	name="<?= implode('_', $md['context']) ?>"
	id="<?= '_' . implode('_', $md['context']) ?>"
	value="1"
	<?= isset($disabled) && $disabled ? "disabled" : null ?>
	<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
	<?= isset($required) && $required ? "required" : null ?>
	<?= $entity->{$name} ? 'checked' : null ?>
	<?= isset($class) ? "class=\"{$class}\"" : null ?>>
<label for="<?= '_' . implode('_', $md['context']) ?>">
	<?= $label ?>
</label>