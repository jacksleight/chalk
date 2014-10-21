<?php
$required = !$md['nullable'];
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
	name="<?= "{$md['contextName']}" ?>"
	id="<?= "_{$md['contextName']}" ?>"
	<?= isset($class) ? "class=\"{$class}\"" : null ?>
	<?= isset($disabled) && $disabled ? "disabled" : null ?>
	<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
	<?= isset($required) && $required ? "required" : null ?>
	<?= isset($autofocus) && $autofocus ? "autofocus" : null ?>>
	<?php foreach ($values as $value => $label) { ?>
		<option
			value="<?= (string) $value ?>"
			<?= (string) $value === (string) $entity->{$name} ? 'selected' : null ?>>
			<?= $this->escape((string) $label) ?>
		</option>
	<?php } ?>
</select>