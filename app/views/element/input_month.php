<?php
echo $this->child('input', [
	'type'	=> 'month',
	'value'	=> $value instanceof \DateTime
		? $value->format("Y-m")
		: $value,
]);