<?php
echo $this->render('input', [
	'type'		=> 'search',
	'value'		=> $entity->{$name},
	'maxlength'	=> $md['validator']->hasValidator('Js\Validator\Length')
		? $md['validator']->getValidator('Js\Validator\Length')->getMax()
		: null,
]);