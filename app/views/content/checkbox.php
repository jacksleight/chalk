<input
	type="hidden"
	name="<?= "contents[{$value}]" ?>"
	value="0">
<input
	type="checkbox"
	name="<?= "contents[{$value}]" ?>"
	id="<?= "_contents[{$value}]" ?>"
	value="1"
	<?= isset($index) && $index->contents->contains($value) ? 'checked' : null ?>> 
<label for="<?= "_contents[{$value}]" ?>" class="checkbox"></label>