<?php
echo $this->inner('input', [
	'type'	=> 'time',
	'value'	=> $value instanceof \DateTime
		? $value->format("H:i")
		: $value,
]);