<?php
$md = $entity->getMetadata(\Js\Entity::MD_PROPERTY, $name);
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
	'manyToOne'	=> 'select',
	'manyToMany'=> 'input_checkboxes',
];
$type = !isset($type)
	? (isset($types[$md['type']])
		? $types[$md['type']]
		: 'text')
	: $type;
$errors = $entity->getErrors([$name]);
?>
<div class="form-group <?= count($errors) > 0 ? 'has-error' : '' ?>">
	<? if ($type != 'input_checkbox') { ?>
		<label
			<?= !in_array($type, ['checkboxes', 'radio']) ? "for=\"_" . implode('_', $md['context']) . "\"" : null ?>
			class="control-label col-lg-2">
			<?= $this->locale->message(isset($label) ? $label : "label_{$name}") ?>
		</label>
		<div class="col-lg-10">	
	<? } else { ?>
		<div class="col-lg-offset-2 col-lg-10">
	<? } ?>	
		<?= $this->render("_control", [
			'md' 	=> $md,
			'type' 	=> $type,
		]) ?>
		<? if (count($errors) > 0) { ?>
			<span class="help-block error">
				<? foreach ($errors[$name] as $name => $params) { ?>
					<?= $this->locale->message($name, $params) ?><br>
				<? } ?>
			</p>
		<? } ?>
		<? if (isset($note)) { ?>
			<span class="help-block"><small><?= $this->locale->message($note) ?></small></p>
		<? } ?>
	</div>
</div>