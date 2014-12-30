<?php
echo $this->render('input', [
	'type'	=> 'month',
	'value'	=> $value instanceof \DateTime
		? $value->format("Y-m")
		: $value,
]);