<?php
if (isset($value) && isset($class) && strpos($class, 'editor-content') !== false) {
	$value = $this->parser->parse($value);
}
?>
<textarea
	<?= isset($name) ? "name=\"{$name}\"" : null ?>
	<?= isset($id) ? "id=\"{$id}\"" : null ?>
	<?= isset($class) ? "class=\"{$class}\"" : null ?>
	<?= isset($rows) ? "rows=\"{$rows}\"" : null ?>
	<?= isset($cols) ? "cols=\"{$cols}\"" : null ?>
	<?= isset($disabled) && $disabled ? "disabled" : null ?>
	<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
	<?= isset($required) && $required ? "required" : null ?>
	<?= isset($maxlength) ? "maxlength=\"{$maxlength}\"" : null ?>
	<?= isset($wrap) ? "wrap=\"{$wrap}\"" : null ?>
	<?= isset($placeholder) ? "placeholder=\"" . $this->escape($placeholder) . "\"" : null ?>><?= isset($value) ? $this->escape($value) : null ?></textarea>