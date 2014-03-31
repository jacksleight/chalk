<?php
echo $this->render('input', [
	'type'		=> 'date',
	'value'		=> isset($entity->{$name})
		? $entity->{$name}->format("Y-m-d")
		: null,
]);