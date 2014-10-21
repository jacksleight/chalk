<?php
echo $this->render('input', [
	'type'		=> 'range',
	'value'		=> $entity->{$name},
	'required'	=> null,
]);