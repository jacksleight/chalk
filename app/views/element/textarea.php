<textarea
	name="<?= "{$name}" ?>"
	id="<?= "{$id}" ?>"
	<?= isset($class) ? "class=\"{$class}\"" : null ?>
	<?= isset($rows) ? "rows=\"{$rows}\"" : null ?>
	<?= isset($cols) ? "cols=\"{$cols}\"" : null ?>
	<?= isset($disabled) && $disabled ? "disabled" : null ?>
	<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
	<?= isset($required) && $required ? "required" : null ?>
	<?= isset($maxlength) ? "maxlength=\"{$maxlength}\"" : null ?>
	<?= isset($wrap) ? "wrap=\"{$wrap}\"" : null ?>
	<?= isset($placeholder) ? "placeholder=\"" . $this->escape($placeholder) . "\"" : null ?>><?= $this->escape($value) ?></textarea>