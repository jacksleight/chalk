<?php
echo $this->render('input', [
	'type'		=> 'datetime',
	'value'		=> isset($entity->{$name})
		? $entity->{$name}->format("Y-m-d\TH:i:s\Z")
		: null,
]);