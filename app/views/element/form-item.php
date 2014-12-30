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
	'coast_array'	=> 'array',
	'coast_url'		=> 'input_url',
	\Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_ONE	=> 'select',
	\Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY	=> 'input_checkboxes',
];
$type = !isset($type)
	? (isset($types[$md['type']])
		? $types[$md['type']]
		: 'input_text')
	: $type;
$values = isset($values)
	? $values
	: (isset($md['values']) ? $md['values'] : []);
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
		<?= $this->render($render[0], [
			'md'		=> $md,
			'type'		=> $type,
			'name'		=> $md['contextName'],
			'id'		=> $md['contextName'],
			'value'		=> $entity->{$name},
			'values'	=> $values,
			'required'	=> !$md['nullable'],
			'maxlength'	=> $md['length'],
			'disabled'	=> isset($disabled)
				? $disabled
				: ($entity->getObject() instanceof \Chalk\Behaviour\Publishable && $entity->isArchived()),
		], $render[1]) ?>
		<?php
		$errors = $entity->getErrors([$name]);
		?>
		<?php if (count($errors) > 0) { ?>
			<p class="error">
				<?php foreach ($errors[$name] as $message => $params) { ?>
					<?= $message ?><br>
				<?php } ?>
			</p>
		<?php } ?>
		<?php if (isset($note)) { ?>
			<p><small><?= $note ?></small></p>
		<?php } ?>
	</div>
</div>