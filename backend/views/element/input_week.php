<?php
echo $this->inner('input', [
	'type'	=> 'week',
	'value'	=> $value instanceof \DateTime
		? $value->format("Y-\WW")
		: $value,
]);