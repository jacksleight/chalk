<?php
$md = $entity->getMetadata(\Toast\Entity::MD_PROPERTY, $name);
$values = isset($values)
	? $values
	: (isset($md['values']) ? $md['values'] : []);
$render = isset($input)
	? $input
	: [$type, null];
?>
<?= $this->render($render[0], [
	'md'		=> $md,
	'type'		=> $type,
	'name'		=> $md['contextName'],
	'id'		=> $md['contextName'],
	'value'		=> $entity->{$name},
	'values'	=> $values,
	'required'	=> !$md['nullable'],
	'maxlength'	=> $md['length'],
	'disabled'	=> isset($disabled)
		? $disabled
		: ($entity->getObject() instanceof \Chalk\Behaviour\Publishable && $entity->isArchived()),
], $render[1]) ?>