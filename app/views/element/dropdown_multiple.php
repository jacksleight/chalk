<?php
$values = isset($values)
	? $values
	: $md['values'];
?>
<div class="dropdown">
	<div class="value <?= isset($icon) ? "icon {$icon}" : null ?>">
		<?php if (count($entity->{$name})) { ?>
			<?= implode(', ', array_intersect_key($values, array_flip($entity->{$name}))) ?>
		<?php } else { ?>
			<span class="placeholder"><?= $placeholder ?></span>
		<?php } ?>		
	</div>
	<?php if (count($values)) { ?>
		<div class="menu">
			<ul>
				<?php foreach ($values as $value => $label) { ?>
					<li>
						<input
							type="hidden"
							name="<?= "{$md['contextName']}[{$value}]" ?>"
							value="0">
						<input
							type="checkbox"
							name="<?= "{$md['contextName']}[{$value}]" ?>"
							id="<?= "_{$md['contextName']}[{$value}]" ?>"
							value="1"
							<?= isset($disabled) && $disabled ? "disabled" : null ?>
							<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
							<?= in_array((string) $value, is_object($entity->{$name})
								? array_map(function($value) { return (string) $value; }, $entity->{$name}->toArray())
								: $entity->{$name}) ? 'checked' : null ?>
							<?= isset($class) ? "class=\"{$class}\"" : null ?>> 
						<label for="<?= "_{$md['contextName']}[{$value}]" ?>" class="item checkbox">
							<?= $this->escape((string) $label) ?>
						</label>
					</li>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>
</div>