<?php
$maxlength	= $md['validator']->hasValidator('Js\Validator\Length')
	? $md['validator']->getValidator('Js\Validator\Length')->getMax()
	: null;
echo $this->render('input', [
	'type'		=> 'text',
	'value'		=> $entity->{$name},
	'maxlength'	=> $maxlength,
]);