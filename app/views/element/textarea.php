<?php
$required	= !$md['nullable'];
$maxlength	= isset($md['length']) ? $md['length'] : null;
?>
<textarea
	name="<?= "{$md['contextName']}" ?>"
	id="<?= "_{$md['contextName']}" ?>"
	<?= isset($class) ? "class=\"{$class}\"" : null ?>
	<?= isset($rows) ? "rows=\"{$rows}\"" : null ?>
	<?= isset($cols) ? "cols=\"{$cols}\"" : null ?>
	<?= isset($disabled) && $disabled ? "disabled" : null ?>
	<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
	<?= false && isset($required) && $required ? "required" : null ?>
	<?= isset($maxlength) ? "maxlength=\"{$maxlength}\"" : null ?>
	<?= isset($wrap) ? "wrap=\"{$wrap}\"" : null ?>
	<?= isset($placeholder) ? "placeholder=\"" . $this->escape($placeholder) . "\"" : null ?>><?= $this->escape($entity->{$name}) ?></textarea>