<?php
if (isset($value) && $value instanceof DateTime) {
    $value->setTimezone(new \DateTimeZone($this->chalk->config->timezone));
}
echo $this->inner('input', [
    'type'  => 'text',
	'class'	=> isset($class) ? "{$class} picker-datetime" : "picker-datetime",
	'value'	=> isset($value) && $value instanceof DateTime
		? $value->format("Y-m-d H:i")
		: $value,
]);