<?php
echo $this->render('input', [
	'type'		=> 'time',
	'value'		=> isset($entity->{$name})
		? $entity->{$name}->format("H:i")
		: null,
]);