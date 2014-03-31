<?php
echo $this->render('input', [
	'type'		=> 'url',
	'value'		=> isset($entity->{$name})
		? (string) $entity->{$name}
		: null,
	'maxlength'	=> $md['validator']->hasValidator('Js\Validator\Length')
		? $md['validator']->getValidator('Js\Validator\Length')->getMax()
		: null,
]);