<input
	type="checkbox"
	id="<?= "_contents[{$entity->id}]" ?>"
	value="<?= $entity->id ?>"
	<?= $entities->contains($entity) ? 'checked' : null ?>> 
<label for="<?= "_contents[{$entity->id}]" ?>" class="checkbox"></label>