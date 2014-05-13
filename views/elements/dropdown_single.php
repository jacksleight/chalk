<?php
$required = $md['validator']->hasValidator('Toast\Validator\Set');
$values = isset($values)
	? $values
	: $md['values'];
if (isset($null)) {
	$values = array_merge([null => $null], $values);
}
?>
<div class="dropdown">
	<div class="value">
		<? if (isset($icon)) { ?>
			<i class="fa fa-<?= $icon ?>"></i>
		<? } ?>
		<? if (count($entity->{$name})) { ?>
			<?= $values[$entity->{$name}] ?>
		<? } else { ?>
			<span class="placeholder"><?= $placeholder ?></span>
		<? } ?>		
	</div>
	<? if (count($values)) { ?>	
		<div class="menu">
			<ul>
				<? foreach ($values as $value => $label) { ?>
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
						<label for="<?= "_{$md['contextName']}[{$value}]" ?>" class="radio">
							<?= $this->escape($label) ?>
						</label>
					</li>
				<? } ?>
			</ul>
		<div>
	<? } ?>
</div>