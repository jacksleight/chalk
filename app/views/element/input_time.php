<?php
echo $this->child('input', [
	'type'	=> 'time',
	'value'	=> $value instanceof \DateTime
		? $value->format("H:i")
		: $value,
]);