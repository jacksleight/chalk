<?php
$required = $md['validator']->hasValidator('Js\Validator\Set');
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
						<label for="<?= '_' . implode('_', $md['context']) . '_' . $value ?>" class="radio">
							<input
								type="radio"
								name="<?= implode('_', $md['context']) ?>"
								id="<?= '_' . implode('_', $md['context']) . '_' . $value ?>"
								value="<?= $this->escape($value) ?>"
								<?= isset($disabled) && $disabled ? "disabled" : null ?>
								<?= isset($readOnly) && $readOnly ? "readonly" : null ?>
								<?= isset($required) && $required ? "required" : null ?>
								<?= (string) $value === (string) $entity->{$name} ? 'checked' : null ?>
								<?= isset($class) ? "class=\"{$class}\"" : null ?>>
							<?= $this->escape($label) ?>
						</label>
					</li>
				<? } ?>
			</ul>
		<div>
	<? } ?>
</div>