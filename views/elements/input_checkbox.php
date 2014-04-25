<?php
$required = $md['validator']->hasValidator('Toast\Validator\Set');
?>
<label for="<?= '_' . implode('_', $md['context']) ?>">
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
	<?= $label ?>
</label>