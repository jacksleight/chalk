<?php
$values = isset($null)
	? [null => $null] + $values
	: $values;
?>
<select
	name="<?= "{$name}" ?>"
	id="<?= "{$id}" ?>"
	<?= isset($class) ? "class=\"{$class}\"" : null ?>
	<?= isset($disabled) && $disabled ? "disabled" : null ?>
	<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
	<?= isset($required) && $required ? "required" : null ?>
	<?= isset($autofocus) && $autofocus ? "autofocus" : null ?>
	<?= isset($formaction) && $formaction ? "formaction=\"{$formaction}\"" : null ?>
	<?= isset($formmethod) && $formmethod ? "formmethod=\"{$formmethod}\"" : null ?>>
	<?php foreach ($values as $v => $l) { ?>
		<option
			value="<?= $v ?>"
			<?= $v == $value ? 'selected' : null ?>>
			<?= $l ?>
		</option>
	<?php } ?>
</select>