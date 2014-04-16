<?php
$values = isset($values)
	? $values
	: $md['values'];
?>
<div class="dropdown">
	<div class="value">
		<? if (isset($icon)) { ?>
			<i class="fa fa-<?= $icon ?>"></i>
		<? } ?>
		<? if (count($entity->{$name})) { ?>
			<?= implode(', ', array_intersect_key($values, array_flip($entity->{$name}))) ?>
		<? } else { ?>
			<span class="placeholder"><?= $placeholder ?></span>
		<? } ?>		
	</div>
	<? if (count($values)) { ?>	
		<ul>
			<? foreach ($values as $value => $label) { ?>
				<li>
					<label for="<?= '_' . "{$md['contextName']}[{$value}]" ?>" class="checkbox">
						<input
							type="hidden"
							name="<?= "{$md['contextName']}[{$value}]" ?>"
							value="0">
						<input
							type="checkbox"
							name="<?= "{$md['contextName']}[{$value}]" ?>"
							id="<?= '_' . "{$md['contextName']}[{$value}]" ?>"
							value="1"
							<?= isset($disabled) && $disabled ? "disabled" : null ?>
							<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
							<?= in_array((string) $value, is_object($entity->{$name})
								? array_map(function($value) { return (string) $value; }, $entity->{$name}->toArray())
								: $entity->{$name}) ? 'checked' : null ?>
							<?= isset($class) ? "class=\"{$class}\"" : null ?>> 
						<?= $this->escape((string) $label) ?>
					</label>
				</li>
			<? } ?>
		</ul>
	<? } ?>
</div>