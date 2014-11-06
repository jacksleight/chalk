<?php
$required = !$md['nullable'];
$values = isset($values)
	? $values
	: $md['values'];
if (isset($null)) {
	$values = array_merge([null => $null], $values);
}
?>
<div class="dropdown">
	<div class="value <?= isset($icon) ? "icon {$icon}" : null ?>">
		<?php if (count($entity->{$name})) { ?>
			<?= $values[$entity->{$name}] ?>
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
							type="radio"
							name="<?= "{$md['contextName']}" ?>"
							id="<?= "_{$md['contextName']}[{$value}]" ?>"
							value="<?= $this->escape($value) ?>"
							<?= isset($disabled) && $disabled ? "disabled" : null ?>
							<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
							<?= isset($required) && $required ? "required" : null ?>
							<?= (string) $value === (string) $entity->{$name} ? 'checked' : null ?>
							<?= isset($class) ? "class=\"{$class}\"" : null ?>>
						<label for="<?= "_{$md['contextName']}[{$value}]" ?>" class="item radio">
							<?= $this->escape($label) ?>
						</label>
					</li>
				<?php } ?>
			</ul>
		<div>
	<?php } ?>
</div>