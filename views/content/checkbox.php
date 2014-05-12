<?php
$md	= isset($md)
	? $md
	: $index->getMetadata(\Toast\Entity::MD_PROPERTY, 'contents');
?>

<input
	type="hidden"
	name="<?= "{$md['contextName']}[{$value}]" ?>"
	value="0">
<input
	type="checkbox"
	name="<?= "{$md['contextName']}[{$value}]" ?>"
	id="<?= '_' . "{$md['contextName']}[{$value}]" ?>"
	value="1"
	<?= in_array((string) $value, array_map(function($value) { return (string) $value; },
		$index->contents->toArray())) ? 'checked' : null ?>> 
<label for="<?= '_' . "{$md['contextName']}[{$value}]" ?>" class="checkbox"></label>