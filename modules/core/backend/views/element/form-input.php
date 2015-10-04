<?php
$md = $entity->getMetadata(\Toast\Entity::MD_PROPERTY, $name);
$values = isset($values)
	? $values
	: (isset($md['values']) ? $md['values'] : []);
if (isset($values[0]) && $values[0] instanceof \Toast\Entity) {
	$temp = [];
	foreach ($values as $value) {
		$temp[$value->id] = $value->name;
	}
	$values = $temp;
}
$value = $entity->{$name};
if ($value instanceof \Toast\Wrapper\Entity) {
	$value = $value->id;
}
$render = isset($input)
	? $input
	: [$type, null];
?>
<?= $this->inner($render[0], [
	'md'		=> $md,
	'type'		=> $type,
	'name'		=> $md['contextName'],
	'id'		=> uniqid('input-'),
	'value'		=> $entity->{$name},
	'values'	=> $values,
	'required'	=> !$md['nullable'],
	'maxlength'	=> isset($md['length']) ? $md['length'] : null,
	'disabled'	=> isset($disabled)
		? $disabled
		: ($entity->getObject() instanceof \Chalk\Core\Behaviour\Publishable && $entity->isArchived()),
], $render[1]) ?>