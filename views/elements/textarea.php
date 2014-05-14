<?php
$required	= $md['validator']->hasValidator('Toast\Validator\Set');
$maxlength	= $md['validator']->hasValidator('Toast\Validator\Length')
	? $md['validator']->getValidator('Toast\Validator\Length')->getMax()
	: null;
?>
<textarea
	name="<?= "{$md['contextName']}" ?>"
	id="<?= "_{$md['contextName']}" ?>"
	<?= isset($class) ? "class=\"{$class}\"" : null ?>
	<?= isset($rows) ? "rows=\"{$rows}\"" : null ?>
	<?= isset($cols) ? "cols=\"{$cols}\"" : null ?>
	<?= isset($disabled) && $disabled ? "disabled" : null ?>
	<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
	<?= isset($required) && $required ? "required" : null ?>
	<?= isset($maxlength) ? "maxlength=\"{$maxlength}\"" : null ?>
	<?= isset($wrap) ? "wrap=\"{$wrap}\"" : null ?>
	<?= isset($placeholder) ? "placeholder=\"" . $this->escape($this->locale->message($placeholder)) . "\"" : null ?>><?= $this->escape($entity->{$name}) ?></textarea>