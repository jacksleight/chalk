<?php
$required = $md['validator']->hasValidator('Js\Validator\Set');
$values = isset($values)
	? $values
	: $md['values'];
if (isset($null) && $null) {
	array_unshift($values, null);
}
?>
<? foreach ($values as $value => $label) { ?>
	<label for="<?= '_' . implode('_', $md['context']) . '_' . $value ?>" class="radio">
		<input
			type="radio"
			name="<?= implode('_', $md['context']) ?>"
			id="<?= '_' . implode('_', $md['context']) . '_' . $value ?>"
			value="<?= $this->escape($value) ?>"
			<?= isset($disabled) && $disabled ? "disabled" : null ?>
			<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
			<?= isset($required) && $required ? "required" : null ?>
			<?= (string) $value === (string) $entity->{$name} ? 'checked' : null ?>
			<?= isset($class) ? "class=\"{$class}\"" : null ?>>
		<?= $this->escape($label) ?>
	</label>
<? } ?>