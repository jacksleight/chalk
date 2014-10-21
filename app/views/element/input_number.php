<?php
echo $this->render('input', [
	'type'		=> 'number',
	'value'		=> $entity->{$name},
]);