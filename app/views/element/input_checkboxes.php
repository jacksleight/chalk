<?php foreach ($values as $v => $l) { ?>
	<input
		type="hidden"
		name="<?= "{$name}[{$this->escape($v)}]" ?>"
		value="0">
	<input
		type="checkbox"
		name="<?= "{$name}[{$this->escape($v)}]" ?>"
		id="<?= "{$id}[{$this->escape($v)}]" ?>"
		value="1"
		<?= isset($disabled) && $disabled ? "disabled" : null ?>
		<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
		<?= in_array($v, $value) ? 'checked' : null ?>
		<?= isset($class) ? "class=\"{$class}\"" : null ?>> 
	<label for="<?= "{$id}[{$this->escape($v)}]" ?>" class="checkbox">
		<?= $l ?>
	</label>
<?php } ?>