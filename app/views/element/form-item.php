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
if ($value instanceof \Toast\Wrapper\Entity) {
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
?>
<div class="form-item <?= !$md['nullable'] ? 'required' : 'optional' ?>">
	<?php if ($type != 'input_checkbox') { ?>
		<label
			<?= !in_array($type, ['checkboxes', 'radio']) ? "for=\"_" . "{$md['contextName']}\"" : null ?>>
			<?= $label ?>
		</label>
	<?php } ?>
	<div>
		<?= $this->child($render[0], [
			'md'		=> $md,
			'type'		=> $type,
			'name'		=> $md['contextName'],
			'id'		=> uniqid('input-'),
			'value'		=> $value,
			'values'	=> $values,
			'required'	=> !$md['nullable'],
			'maxlength'	=> isset($md['length']) ? $md['length'] : null,
			'disabled'	=> isset($disabled)
				? $disabled
				: ($entity->getObject() instanceof \Chalk\Behaviour\Publishable && $entity->isArchived()),
		], $render[1]) ?>
		<?php
		$errors = $entity->getErrors([$name]);
		?>
		<?php if (count($errors) > 0) { ?>
			<?php
			$mgs = [
				'boolean'				=> "This must be true or false",
				'decimal'				=> "This must be a number",
				'float'					=> "This must be a number",
				'emailAddress'			=> "This must be a valid email address",
				'hostname'				=> "This must be a valid hostname",
				'integer'				=> "This must be a whole number",
				'length_min'			=> "This must be at least %1\$s characters long",
				'length_max'			=> "This must be no more than %2\$s characters long",
				'password_lowercase'	=> "This must contain at least %1\$s lowercase letters",
				'password_uppercase'	=> "This must contain at least %2\$s uppercase letters",
				'password_digit'		=> "This must contain at least %3\$s digits",
				'password_special'		=> "This must contain at least %4\$s special characters",
				'range_min'				=> "This must be at least %1\$s",
				'range_max'				=> "This must be no more than %2\$s",
				'count_min'				=> "This must include at least %1\$s",
				'count_max'				=> "This must include no more than %2\$s",
				'set'					=> "This is required",
				'dateTime'				=> "This must be a valid date/time",
				'url'					=> "This must be a valid URL",
				'login'					=> "This account cannot be found",
			];
			?>
			<p class="error">
				<?php foreach ($errors[$name] as $code => $params) { ?>
					<? if (isset($mgs[$code])) { ?>
						<?= vsprintf($mgs[$code], $params) ?>
					<? } else { ?>
						<?= $code ?>
					<? } ?>
					<br>
				<?php } ?>
			</p>
		<?php } ?>
		<?php if (isset($note)) { ?>
			<p><small><?= $note ?></small></p>
		<?php } ?>
	</div>
</div>





