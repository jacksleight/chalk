<?php
$messages = array(
	'validator_bannedWords_invalid'		=> "This must not contain any of these words: {0}",
	'validator_boolean_invalid'			=> "This must be a true or false",
	'validator_decimal_invalid'			=> "This must be a decimal number",
	'validator_emailAddress_invalid'	=> "This must be a valid email address",
	'validator_equals_invalid'			=> "This must be {0}",
	'validator_htmlId_invalid'			=> "This must be a valid HTML ID",
	'validator_hostname_invalid'		=> "This must be a valid hostname",
	'validator_integer_invalid'			=> "This must be a whole number",
	'validator_length_short'			=> "This must be at least {0,spellout} characters long",
	'validator_length_long'				=> "This must be no more than {1,spellout} characters long",
	'validator_list_invalid'			=> "This must be one of these: {0}",
	'validator_password_fewDigit'		=> "This must contain at least {0,spellout} digits",
	'validator_password_fewUppercase'	=> "This must contain at least {1,spellout} uppercase letters",
	'validator_password_fewSpecial'		=> "This must contain at least {2,spellout} special characters",
	'validator_range_low'				=> "This must be at least {0,spellout}",
	'validator_range_high'				=> "This must be no more than {1,spellout}",
	'validator_set_invalid'				=> "This is mandatory",
	'validator_upload_connectionError'	=> "A connection error occured, please try again",
	'validator_upload_serverError'		=> "A server error occured, please try again",
	'validator_upload_tooBig'			=> "This must be no bigger than {0}",
	'validator_upload_tooBigServer'		=> "This must be no bigger than {0}",
	'validator_upload_invalidMimeType'	=> "This must be one of these types: {1}",
	'validator_dateTime_invalid'		=> "This must be a valid date/time",
	'validator_url_invalid'				=> "This must be a valid URL",
	'validator_words_low'				=> "This must be at least {0,spellout} words long",
	'validator_words_high'				=> "This must be no more than {1,spellout} words long",
	'validator_login_invalid'			=> "Sorry, that account could not be found",
);
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
	'coast_json'	=> 'array',
	'coast_url'		=> 'input_url',
	\Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_ONE	=> 'select',
	\Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY	=> 'input_checkboxes',
];
$type = !isset($type)
	? (isset($types[$md['type']])
		? $types[$md['type']]
		: 'input_text')
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
					<?php
					$formatter = new \MessageFormatter('en-GB', $messages[$name]);
					?>
					<?= $formatter->format($params) ?><br>
				<? } ?>
			</p>
		<? } ?>
		<? if (isset($note)) { ?>
			<p><small><?= $note ?></small></p>
		<? } ?>
	</div>
</div>