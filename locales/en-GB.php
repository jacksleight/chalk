<?php
$messages = array(
	'label' => array(
		'name'							=> "Name",
		'emailAddress'					=> "Email Address",
		'message'						=> "Message",
		'dateTime'						=> "Date & Time",
		'ipAddress'						=> "IP Address",
		'subject' 						=> array("Subject", array(
			'sales'							=> "Sales",
			'billing'						=> "Billing",
			'support'						=> "Support",
		)),
		'radio' 						=> array("Radio", array(
			'yes'						=> "Yes",
			'no'						=> "No",
		)),
		'select' 						=> array("Select", array(
			'null'						=> "Select…",
			'yes'						=> "Yes",
			'no'						=> "No",
		)),
	),
	'validator' => array(
		'bannedWords' => array(
			'invalid'					=> "This must not contain any of these words: {0}",
		),
		'boolean' => array(
			'invalid'					=> "This must be a true or false",
		),
		'decimal' => array(
			'invalid'					=> "This must be a decimal number",
		),
		'emailAddress' => array(
			'invalid'					=> "This must be a valid email address",
		),
		'equals' => array(
			'invalid'					=> "This must be {0}",
		),
		'htmlId' => array(
			'invalid'					=> "This must be a valid HTML ID",
		),
		'hostname' => array(
			'invalid'					=> "This must be a valid hostname",
		),
		'integer' => array(
			'invalid'					=> "This must be a whole number",
		),
		'length' => array(
			'short'						=> "This must be at least {0,spellout} characters long",
			'long'						=> "This must be no more than {1,spellout} characters long",
		),
		'list' => array(
			'invalid'					=> "This must be one of these: {0}",
		),
		'password' => array(
			'fewDigit'					=> "This must contain at least {0,spellout} digits",
			'fewUppercase'				=> "This must contain at least {1,spellout} uppercase letters",
			'fewSpecial'				=> "This must contain at least {2,spellout} special characters",
		),
		'range' => array(
			'low'						=> "This must be at least {0,spellout}",
			'high'						=> "This must be no more than {1,spellout}",
		),
		'set' => array(
			'invalid'					=> "This is mandatory",
		),
		'upload' => array(
			'connectionError'			=> "A connection error occured, please try again",
			'serverError'				=> "A server error occured, please try again",
			'tooBig'					=> "This must be no bigger than {0}",
			'tooBigServer'				=> "This must be no bigger than {0}",
			'invalidMimeType'			=> "This must be one of these types: {1}",
		),
		'dateTime' => array(
			'invalid'					=> "This must be a valid date/time",
		),
		'url' => array(
			'invalid'					=> "This must be a valid URL",
		),
		'words' => array(
			'low'						=> "This must be at least {0,spellout} words long",
			'high'						=> "This must be no more than {1,spellout} words long",
		),
	),
);