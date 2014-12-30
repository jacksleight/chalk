<?php
echo $this->render('input', [
	'type'	=> 'datetime',
	'value'	=> isset($value)
		? $value->format("Y-m-d\TH:i:s\Z")
		: $value,
]);