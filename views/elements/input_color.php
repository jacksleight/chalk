<?php
echo $this->render('input', [
	'type'		=> 'color',
	'value'		=> $entity->{$name},
	'required'	=> null,
]);