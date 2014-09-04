<?php
echo $this->render('input', [
	'type'		=> 'month',
	'value'		=> isset($entity->{$name})
		? $entity->{$name}->format("Y-m")
		: null,
]);