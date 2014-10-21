<?php
echo $this->render('input', [
	'type'		=> 'email',
	'value'		=> $entity->{$name},
	'maxlength'	=> isset($md['length']) ? $md['length'] : null,
]);