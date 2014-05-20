<?php
$md = $entity->getMetadata(\Toast\Entity::MD_PROPERTY, $name);
$types = [
	'string'	=> 'input_text',
	'text'		=> 'textarea',
	'integer'	=> 'input_number',
	'smallint'	=> 'input_number',
	'bigint'	=> 'input_number',
	'decimal'	=> 'input_number',
	'float'		=> 'input_number',
	'boolean'	=> 'input_checkbox',
	'date'		=> 'input_date',
	'time'		=> 'input_time',
	'datetime'	=> 'input_datetime-local',
	'url'		=> 'input_url',
	\Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_ONE	=> 'select',
	\Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY	=> 'input_checkboxes',
];
$type = !isset($type)
	? (isset($types[$md['type']])
		? $types[$md['type']]
		: 'text')
	: $type;
?>
<div class="form-item <?= $md['validator']->hasValidator('Toast\Validator\Set') ? 'required' : 'optional' ?>">
	<label
		<?= !in_array($type, ['checkboxes', 'radio']) ? "for=\"_" . "{$md['contextName']}\"" : null ?>
		<?=  in_array($type, ['checkbox', 'radio', 'range', 'color']) ? "class=\"shallow\"" : null ?>>
		<? if ($type != 'input_checkbox') { ?>
			<?= $label ?>
		<? } ?>
	</label>
	<div>
		<?= $this->render("form-input", [
			'md' 	=> $md,
			'type' 	=> $type,
		]) ?>
		<?php
		$errors = $entity->getErrors([$name]);
		?>
		<? if (count($errors) > 0) { ?>
			<p class="error">
				<? foreach ($errors[$name] as $name => $params) { ?>
					<?= $this->locale->message($name, $params) ?><br>
				<? } ?>
			</p>
		<? } ?>
		<? if (isset($note)) { ?>
			<p><small><?= $note ?></small></p>
		<? } ?>
	</div>
</div>