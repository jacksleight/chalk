<?php
$required = $md['validator']->hasValidator('Toast\Validator\Set');
$values = isset($values)
	? $values
	: $md['values'];
$temp = [];
foreach ($values as $value) {
	$temp[(string) $value->id] = (string) $value;
}
$values = $temp;
if (!isset($null)) {
	$values = [null => 'Select…'] + $values;
} else if ($null !== false) {
	$values = [null => $null] + $values;
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
	<?php foreach ($values as $i => $value) { ?>
		<option
			value="<?= (string) $i ?>"
			<?= isset($entity->{$name}) && (string) $i === (string) $entity->{$name}->id ? 'selected' : null ?>>
			<?= $this->escape((string) $value) ?>
		</option>
	<?php } ?>
</select>