<?php
echo $this->inner('input', [
	'type'	=> 'datetime-local',
	'value'	=> isset($value)
		? $value->format("Y-m-d\TH:i:s")
		: $value,
]);