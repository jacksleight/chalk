<?php
echo $this->render('input', [
	'type'		=> 'week',
	'value'		=> isset($entity->{$name})
		? $entity->{$name}->format("Y-\WW")
		: null,
]);