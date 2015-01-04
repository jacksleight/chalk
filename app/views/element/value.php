<span
	class="value
		<?= isset($disabled) && $disabled ? "disabled" : null ?>
		<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
		<?= isset($class) ? $class : null ?>">
	<?= $this->escape($value) ?>
</span>