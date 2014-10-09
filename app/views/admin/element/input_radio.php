<?php
$required = $md['validator']->hasValidator('Toast\Validator\Set');
$values = isset($values)
	? $values
	: $md['values'];
if (isset($null)) {
	$values = array_merge([null => $null], $values);
}
?>
<?php foreach ($values as $value => $label) { ?>
	<input
		type="radio"
		name="<?= "{$md['contextName']}" ?>"
		id="<?= "_{$md['contextName']}[{$value}]" ?>"
		value="<?= $this->escape($value) ?>"
		<?= isset($disabled) && $disabled ? "disabled" : null ?>
		<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
		<?= isset($required) && $required ? "required" : null ?>
		<?= (string) $value === (string) $entity->{$name} ? 'checked' : null ?>
		<?= isset($class) ? "class=\"{$class}\"" : null ?>>
	<label for="<?= "_{$md['contextName']}[{$value}]" ?>" class="radio">
		<?= $this->escape($label) ?>
	</label>
<?php } ?>