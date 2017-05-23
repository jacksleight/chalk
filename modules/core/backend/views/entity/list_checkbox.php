<input
    type="checkbox"
    id="<?= "__select[{$entity->id}]" ?>"
    value="<?= $entity->id ?>"
    <?= array_intersect([$entity->id], $model->selected) ? 'checked' : null ?>> 
<label for="<?= "__select[{$entity->id}]" ?>" class="checkbox"></label>