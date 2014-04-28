<?php
$required = $md['validator']->hasValidator('Toast\Validator\Set');
$values = isset($values)
	? $values
	: $md['values'];
if (!isset($null)) {
	$values = array_merge([null => 'Select…'], $values);
} else if ($null !== false) {
	$values = array_merge([null => $null], $values);
}
?>
<select
	name="<?= implode('_', $md['context']) ?>"
	id="<?= '_' . implode('_', $md['context']) ?>"
	<?= isset($class) ? "class=\"{$class}\"" : null ?>
	<?= isset($disabled) && $disabled ? "disabled" : null ?>
	<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
	<?= isset($required) && $required ? "required" : null ?>
	<?= isset($autofocus) && $autofocus ? "autofocus" : null ?>>
	<? foreach ($values as $value => $label) { ?>
		<option
			value="<?= (string) $value ?>"
			<?= (string) $value === (string) $entity->{$name} ? 'selected' : null ?>>
			<?= $this->escape((string) $label) ?>
		</option>
	<? } ?>
</select>