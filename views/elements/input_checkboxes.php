<?php
$values = isset($values)
	? $values
	: $md['values'];
?>
<? foreach ($values as $value) { ?>
	<input
		type="hidden"
		name="<?= "{$md['contextName']}[{$value}]" ?>"
		value="0">
	<input
		type="checkbox"
		name="<?= "{$md['contextName']}[{$value}]" ?>"
		id="<?= "_{$md['contextName']}[{$value}]" ?>"
		value="1"
		<?= isset($disabled) && $disabled ? "disabled" : null ?>
		<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
		<?= in_array((string) $value, is_object($entity->{$name})
			? array_map(function($value) { return (string) $value; }, $entity->{$name}->toArray())
			: $entity->{$name}) ? 'checked' : null ?>
		<?= isset($class) ? "class=\"{$class}\"" : null ?>> 
	<label for="<?= "_{$md['contextName']}[{$value}]" ?>" class="checkbox">
		<?= $this->escape($this->locale->message((is_object($value)
			? $value->name
			: $this->locale->message("label_{$name}_{$value}")))) ?>
	</label>
<? } ?>