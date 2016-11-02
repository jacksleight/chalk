<?php
echo $this->inner('input', [
	'type'	=> 'text',
    'class' => isset($class) ? "{$class} picker-date" : "picker-date",
    'value' => isset($value) && $value instanceof DateTime
		? $value->format("Y-m-d")
		: $value,
]);