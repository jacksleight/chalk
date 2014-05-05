<?php
echo $this->render('input', [
	'type'		=> 'url',
	'value'		=> isset($entity->{$name})
		? (string) $entity->{$name}
		: null,
	'maxlength'	=> $md['validator']->hasValidator('Toast\Validator\Length')
		? $md['validator']->getValidator('Toast\Validator\Length')->getMax()
		: null,
]);