<?php
echo $this->render('input', [
	'type'		=> 'tel',
	'value'		=> $entity->{$name},
	'maxlength'	=> $md['validator']->hasValidator('Js\Validator\Length')
		? $md['validator']->getValidator('Js\Validator\Length')->getMax()
		: null,
]);