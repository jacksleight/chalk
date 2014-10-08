<?php
echo $this->render('input', [
	'type'		=> 'password',
	'value'		=> $entity->{$name},
	'maxlength'	=> $md['validator']->hasValidator('Toast\Validator\Length')
		? $md['validator']->getValidator('Toast\Validator\Length')->getMax()
		: null,
]);