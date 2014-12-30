<?php
echo $this->render('input', [
	'type'	=> 'time',
	'value'	=> $value instanceof \DateTime
		? $value->format("H:i")
		: $value,
]);