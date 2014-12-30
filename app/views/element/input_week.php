<?php
echo $this->child('input', [
	'type'	=> 'week',
	'value'	=> $value instanceof \DateTime
		? $value->format("Y-\WW")
		: $value,
]);