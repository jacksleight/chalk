<?php
$maxlength	= $md['validator']->hasValidator('Toast\Validator\Length')
	? $md['validator']->getValidator('Toast\Validator\Length')->getMax()
	: null;
echo $this->render('input', [
	'type'		=> 'text',
	'value'		=> $entity->{$name},
	'maxlength'	=> $maxlength,
]);