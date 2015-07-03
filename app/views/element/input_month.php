<?php
echo $this->inner('input', [
	'type'	=> 'month',
	'value'	=> $value instanceof \DateTime
		? $value->format("Y-m")
		: $value,
]);