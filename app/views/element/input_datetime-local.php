<?php
echo $this->render('input', [
	'type'	=> 'datetime-local',
	'value'	=> isset($value)
		? $value->format("Y-m-d\TH:i:s")
		: $value,
]);