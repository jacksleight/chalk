<input
	type="hidden"
	name="<?= "{$name}" ?>"
	value="0">
<input
	type="checkbox"
	name="<?= "{$name}" ?>"
	id="<?= "{$id}" ?>"
	value="1"
	<?= isset($disabled) && $disabled ? "disabled" : null ?>
	<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
	<?= $value ? 'checked' : null ?>
	<?= isset($class) ? "class=\"{$class}\"" : null ?>>
<label for="<?= "{$id}" ?>">
	<?= $label ?>
</label>