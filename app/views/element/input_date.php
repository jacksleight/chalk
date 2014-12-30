<?php
echo $this->render('input', [
	'type'	=> 'date',
	'value'	=> $value instanceof \DateTime
		? $value->format("Y-m-d")
		: $value,
]);