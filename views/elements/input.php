<?php
$required = $md['validator']->hasValidator('Toast\Validator\Set');
?>
<input
	type="<?= $type ?>"
	name="<?= "{$md['contextName']}" ?>"
	id="<?= "_{$md['contextName']}" ?>"
	<?= isset($value) ? "value=\"" . $this->escape($value) . "\"" : null ?>
	<?= isset($class) ? "class=\"{$class}\"" : null ?>
	<?= isset($disabled) && $disabled ? "disabled" : null ?>
	<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
	<?= isset($required) && $required ? "required" : null ?>
	<?= isset($pattern) ? "pattern=\"{$pattern}\"" : null ?>
	<?= isset($accept) ? "accept=\"{$accept}\"" : null ?>
	<?= isset($maxlength) ? "maxlength=\"{$maxlength}\"" : null ?>
	<?= isset($min) ? "min=\"{$min}\"" : null ?>
	<?= isset($max) ? "max=\"{$max}\"" : null ?>
	<?= isset($step) ? "step=\"{$step}\"" : null ?>
	<?= isset($list) ? "list=\"{$list}\"" : null ?>
	<?= isset($multiple) ? "multiple=\"{$multiple}\"" : null ?>
	<?= isset($placeholder) ? "placeholder=\"" . $this->escape($placeholder) . "\"" : null ?>
	<?= isset($autofocus) && $autofocus ? "autofocus" : null ?>
	<?= isset($autocomplete) ? "autocomplete=\"" . ($autocomplete ? 'on' : 'off') . "\"" : null ?>
	<?= isset($spellcheck) ? "spellcheck=\"" . ($spellcheck ? 'true' : 'false') . "\"" : null ?>>