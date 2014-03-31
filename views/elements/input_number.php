<?php
$range = $md['validator']->hasValidator('Js\Validator\Range')
	? $md['validator']->getValidator('Js\Validator\Range')
	: null;
echo $this->render('input', [
	'type'		=> 'number',
	'value'		=> $entity->{$name},
	'min'		=> isset($range)
		? $range->getMin()
		: null,
	'max'		=> isset($range)
		? $range->getMax()
		: null,
]);