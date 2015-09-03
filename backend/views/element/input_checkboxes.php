<?php foreach ($values as $v => $l) { ?>
	<span style="display: inline-block; width: 49%;">	
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
			<span></span> <?= $l ?>
		</label>
	</span>
<?php } ?>
