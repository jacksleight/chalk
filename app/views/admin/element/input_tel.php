<?php
echo $this->render('input', [
	'type'		=> 'tel',
	'value'		=> $entity->{$name},
	'maxlength'	=> isset($md['length']) ? $md['length'] : null,
]);