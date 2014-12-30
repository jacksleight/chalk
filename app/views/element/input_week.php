<?php
echo $this->render('input', [
	'type'	=> 'week',
	'value'	=> $value instanceof \DateTime
		? $value->format("Y-\WW")
		: $value,
]);