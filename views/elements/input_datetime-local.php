<?php
echo $this->render('input', [
	'type'		=> 'datetime-local',
	'value'		=> isset($entity->{$name})
		? $entity->{$name}->format("Y-m-d\TH:i:s")
		: null,
]);