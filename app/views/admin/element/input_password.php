<?php
echo $this->render('input', [
	'type'		=> 'password',
	'value'		=> $entity->{$name},
	'maxlength'	=> isset($md['length']) ? $md['length'] : null,
]);