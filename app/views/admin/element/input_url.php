<?php
echo $this->render('input', [
	'type'		=> 'url',
	'value'		=> isset($entity->{$name})
		? (string) $entity->{$name}
		: null,
	'maxlength'	=> isset($md['length']) ? $md['length'] : null,
]);