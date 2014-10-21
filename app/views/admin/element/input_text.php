<?php
echo $this->render('input', [
	'type'		=> 'text',
	'value'		=> $entity->{$name},
	'maxlength'	=> isset($md['length']) ? $md['length'] : null,
]);