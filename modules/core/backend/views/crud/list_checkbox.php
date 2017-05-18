<input
    type="checkbox"
    id="<?= "_contents[{$entity->id}]" ?>"
    value="<?= $entity->id ?>"
    <?= in_array($entity->id, $selectedArray) ? 'checked' : null ?>> 
<label for="<?= "_contents[{$entity->id}]" ?>" class="checkbox"></label>