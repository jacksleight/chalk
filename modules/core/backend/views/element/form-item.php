<?php
$md = $entity->getMetadata(\Toast\Entity::MD_PROPERTY, $name);
$types = [
	'string'		=> 'input_text',
	'text'			=> 'textarea',
	'integer'		=> 'input_number',
	'smallint'		=> 'input_number',
	'bigint'		=> 'input_number',
	'decimal'		=> 'input_number',
	'float'			=> 'input_number',
	'boolean'		=> 'input_checkbox',
	'date'			=> 'input_date',
	'time'			=> 'input_time',
	'datetime'		=> 'input_datetime-local',
	'array'			=> 'array',
	'json_array'	=> 'array',
	'coast_array'	=> 'array',
	'coast_url'		=> 'input_url',
	'chalk_entity'	=> 'input_chalk',
	'manyToOne'		=> 'select',
	'manyToMany'	=> 'input_checkboxes',
];
$type = !isset($type)
	? (isset($types[$md['type']])
		? $types[$md['type']]
		: 'input_text')
	: $type;
$values = isset($values)
	? $values
	: (isset($md['values']) ? $md['values'] : []);
if (isset($values[0]) && $values[0] instanceof \Toast\Entity) {
	$temp = [];
	foreach ($values as $v) {
		$temp[$v->id] = $v->name;
	}
	$values = $temp;
}
$value = $entity->{$name};
if ($value instanceof \Toast\Wrapper\Entity && $md['type'] != 'chalk_entity') {
	$value = $value->id;
} else if ($value instanceof \Toast\Wrapper\Collection) {
	$temp = [];
	foreach ($value as $v) {
		$temp[] = $v->id;
	}
	$value = $temp;
}
$render = isset($input)
	? $input
	: [$type, null];
$validatorSet = $md['validator']->rule('set');
?>
<div class="form-item <?= $type == 'input_pseudo' || count($validatorSet) ? 'required' : 'optional' ?>">
	<?php if (isset($label) && $type != 'input_checkbox') { ?>
		<label
			<?= !in_array($type, ['checkboxes', 'radio']) ? "for=\"_" . "{$md['contextName']}\"" : null ?>>
			<?= $label ?>
		</label>
	<?php } ?>
	<div>
		<?= isset($before) ? "{$before} &nbsp;&nbsp;" : null ?>
		<?= $this->inner($render[0], [
			'md'		=> $md,
			'type'		=> $type,
			'name'		=> $md['contextName'],
			'id'		=> uniqid('input-'),
			'value'		=> $value,
			'values'	=> $values,
			'required'	=> !$md['nullable'],
			'maxlength'	=> isset($md['length']) ? $md['length'] : null,
		], $render[1]) ?>
		<?= isset($after) ? "&nbsp;&nbsp; {$after}" : null ?>
		<?php
		$errors = $entity->getErrors([$name]);
		?>
		<?php if (count($errors) > 0) { ?>
			<?php
			$mgs = [
				'boolean'				=> "Please select either yes or no",
				'decimal'				=> "Please enter a number",
				'float'					=> "Please enter a number",
				'emailAddress'			=> "Please enter a valid email address",
				'hostname'				=> "Please enter a valid hostname",
				'integer'				=> "Please enter a whole number",
				'length_min'			=> "Please enter at least %1\$s characters",
				'length_max'			=> "Please enter no more than %2\$s characters",
				'password_lowercase'	=> "Please enter at least %1\$s lowercase letters",
				'password_uppercase'	=> "Please enter at least %2\$s uppercase letters",
				'password_digit'		=> "Please enter at least %3\$s digits",
				'password_special'		=> "Please enter at least %4\$s special characters",
				'range_min'				=> "Please enter at least %1\$s",
				'range_max'				=> "Please enter no more than %2\$s",
				'set'					=> "Please complete this field",
				'dateTime'				=> "Please enter a valid date/time",
				'url'					=> "Please enter a valid URL",
				'login'					=> "Please check your login, an account with that username and password could not be found",
				'count_min'				=> "Please select at least %1\$s",
				'count_max'				=> "Please select no more than %2\$s",
			];
			?>
			<p class="error">
				<?php foreach ($errors[$name] as $code => $params) { ?>
					<?php if (isset($mgs[$code])) { ?>
						<?= vsprintf($mgs[$code], $params) ?>
					<?php } else { ?>
						<?= $code ?>
					<?php } ?>
					<br>
				<?php } ?>
			</p>
		<?php } ?>
		<?php if (isset($note)) { ?>
			<p><small><?= $note ?></small></p>
		<?php } ?>
	</div>
</div>