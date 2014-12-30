<div class="dropdown">
	<div class="value <?= isset($icon) ? "icon {$icon}" : null ?>">
		<?php if (count($value)) { ?>
			<?= implode(', ', array_intersect_key($values, array_flip($value))) ?>
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
						<label for="<?= "{$id}[{$this->escape($v)}]" ?>" class="item checkbox">
							<?= $l ?>
						</label>
					</li>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>
</div>