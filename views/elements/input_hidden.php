<?php
echo $this->render('input', [
	'type'		=> 'hidden',
	'value'		=> $entity->{$name},
]);