<?php
$values = isset($null)
	? [null => $null] + $values
	: $values;
?>
<div class="dropdown">
	<div class="input-pseudo <?= isset($icon) ? $icon : null ?>">
		<?php if (isset($value)) { ?>
			<?= $values[$value] ?>
		<?php } else { ?>
			<span class="placeholder"><?= $placeholder ?></span>
		<?php } ?>		
	</div>
	<?php if (count($values)) { ?>
		<div class="menu">
			<ul>
				<?php foreach ($values as $v => $l) { ?>
					<li>
						<input
							type="radio"
							name="<?= "{$name}" ?>"
							id="<?= "{$id}[{$this->escape($v)}]" ?>"
							value="<?= $this->escape($v) ?>"
							<?= isset($disabled) && $disabled ? "disabled" : null ?>
							<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
							<?= isset($required) && $required ? "required" : null ?>
							<?= $v == $value ? 'checked' : null ?>
							<?= isset($class) ? "class=\"{$class}\"" : null ?>>
						<label for="<?= "{$id}[{$this->escape($v)}]" ?>" class="item radio">
							<?= $l ?>
						</label>
					</li>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>
</div>