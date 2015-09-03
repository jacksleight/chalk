<?php
$values = isset($null)
	? [null => $null] + $values
	: $values;
?>
<?php foreach ($values as $v => $l) { ?>
	<input
		type="radio"
		name="<?= "{$name}" ?>"
		id="<?= "{$id}[{$this->escape($v)}]" ?>"
		value="<?= $this->escape($v) ?>"
		<?= isset($disabled) && $disabled ? "disabled" : null ?>
		<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
		<?= isset($required) && $required ? "required" : null ?>
		<?= $v == $value ? 'checked' : null ?>
		<?= isset($class) ? "class=\"{$class}\"" : null ?>>
	<label for="<?= "{$id}[{$this->escape($v)}]" ?>" class="radio">
		<span></span> <?= $l ?>
	</label>
<?php } ?>