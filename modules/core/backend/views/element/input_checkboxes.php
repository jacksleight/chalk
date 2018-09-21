<input
	type="hidden"
	name="<?= "{$name}[]" ?>"
	value="">
<ul class="checkboxes">
	<?php
	$i = 0;
	?>
	<?php foreach ($values as $v => $l) { ?>
		<li class="checkboxes_i">
			<input
				type="checkbox"
				name="<?= "{$name}[{$this->escape($i)}]" ?>"
				id="<?= "{$id}[{$this->escape($i)}]" ?>"
				value="<?= $this->escape($v) ?>"
				<?= isset($disabled) && $disabled ? "disabled" : null ?>
				<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
				<?= in_array($v, $value) ? 'checked' : null ?>
				<?= isset($class) ? "class=\"{$class}\"" : null ?>>
			<label for="<?= "{$id}[{$this->escape($i)}]" ?>" class="checkbox">
				<?= $l ?>
			</label>
		</li>
		<?php
		$i++;
		?>
	<?php } ?>
	<?= str_repeat('<li></li>', 10) ?>
</ul>
<?php if (!count($values)) { ?>
	<small>No options available</small>
<?php } ?>